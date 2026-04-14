<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
    * @var IncomingRequest|CLIRequest
    */
    protected $request;
    protected $helpers = ['url', 'form', 'text', 'filesystem'];
    protected $session;
    protected $db;
    protected $validation;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session    = \Config\Services::session();
        $this->db         = \Config\Database::connect();
        $this->validation = \Config\Services::validation();
    }

    // ---- Render view untuk admin ----
    protected function adminView(string $view, array $data = []): string
    {
        $data['session']          = $this->session;
        // Admin badge = jumlah peminjaman PENDING (bukan notifikasi)
        $data['unreadNotifCount'] = $this->getPendingLoanCount();
        return view('layouts/admin_layout', array_merge($data, ['content_view' => $view]));
    }

    // ---- Render view untuk user ----
    protected function userView(string $view, array $data = []): string
    {
        $data['session']          = $this->session;
        // User badge = notifikasi belum dibaca
        $data['unreadNotifCount'] = $this->getUnreadNotifCount();
        return view('layouts/user_layout', array_merge($data, ['content_view' => $view]));
    }

    // ---- Hitung peminjaman pending (badge admin) ----
    protected function getPendingLoanCount(): int
    {
        return (int) $this->db->table('peminjaman')
            ->where('status', 'pending')
            ->countAllResults();
    }

    // ---- Hitung notifikasi belum dibaca (badge user) ----
    protected function getUnreadNotifCount(): int
    {
        $userId = $this->session->get('id_user');
        if (!$userId) return 0;
        return (int) $this->db->table('notifikasi')
            ->where('id_user', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    // ---- Kirim notifikasi ke user ----
    protected function sendNotification(int $userId, string $judul, string $pesan, string $link = ''): void
    {
        $this->db->table('notifikasi')->insert([
            'id_user'    => $userId,
            'judul'      => $judul,
            'pesan'      => $pesan,
            'link'       => $link,
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
