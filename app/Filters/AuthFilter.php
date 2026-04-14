<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Belum login → redirect ke login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek role jika argumen disediakan
        if ($arguments) {
            $requiredRole = $arguments[0] ?? null;
            $userRole     = $session->get('role');

            if ($requiredRole && $userRole !== $requiredRole) {
                // Admin coba akses user → kirim ke admin dashboard
                if ($userRole === 'admin') {
                    return redirect()->to('/admin/dashboard');
                }
                // User coba akses admin
                return redirect()->to('/user/dashboard')->with('error', 'Akses ditolak.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
