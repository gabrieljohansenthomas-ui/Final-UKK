<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\NotifikasiModel;

class NotificationController extends BaseController
{
    public function index()
    {
        $idUser = $this->session->get('id_user');
        $notifs = $this->db->table('notifikasi')
            ->where('id_user', $idUser)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        return $this->userView('user/notifications/index', compact('notifs'));
    }

    public function markRead(int $id)
    {
        $model = new NotifikasiModel();
        $notif = $model->find($id);
        if ($notif && $notif['id_user'] == $this->session->get('id_user')) {
            $model->update($id, ['is_read' => 1]);
            if ($notif['link']) return redirect()->to($notif['link']);
        }
        return redirect()->to('/user/notifications');
    }

    public function markAllRead()
    {
        $idUser = $this->session->get('id_user');
        $this->db->table('notifikasi')->where('id_user', $idUser)->update(['is_read' => 1]);
        return redirect()->to('/user/notifications')->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
