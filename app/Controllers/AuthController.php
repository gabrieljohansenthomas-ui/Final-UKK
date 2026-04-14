<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AnggotaModel;

class AuthController extends BaseController
{
    public function index()
    {
        if ($this->session->get('logged_in')) {
            return $this->redirectByRole();
        }
        return view('auth/login');
    }

    public function login()
    {
        if ($this->session->get('logged_in')) {
            return $this->redirectByRole();
        }

        $rules = [
            'login'    => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $login)
                          ->orWhere('email', $login)
                          ->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username/email atau password salah.');
        }

        if ($user['status'] == 0) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda telah dinonaktifkan. Hubungi admin.');
        }

        // Update last_login
        $userModel->update($user['id_user'], ['last_login' => date('Y-m-d H:i:s')]);

        // Set session
        $this->session->set([
            'logged_in'    => true,
            'id_user'      => $user['id_user'],
            'username'     => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'foto_profil'  => $user['foto_profil'],
        ]);

        // Ambil id_anggota jika user biasa
        if ($user['role'] === 'user') {
            $anggotaModel = new AnggotaModel();
            $anggota = $anggotaModel->where('id_user', $user['id_user'])->first();
            if ($anggota) {
                $this->session->set('id_anggota', $anggota['id_anggota']);
            }
        }

        return $this->redirectByRole();
    }

    public function register()
    {
        if ($this->session->get('logged_in')) {
            return $this->redirectByRole();
        }
        return view('auth/register');
    }

    public function doRegister()
    {
        $rules = [
            'nama_lengkap'         => 'required|min_length[3]|max_length[100]',
            'email'                => 'required|valid_email|is_unique[users.email]',
            'username'             => 'required|min_length[4]|max_length[50]|is_unique[users.username]|alpha_numeric_punct',
            'password'             => 'required|min_length[6]',
            'confirm_password'     => 'required|matches[password]',
        ];

        $messages = [
            'email'    => ['is_unique' => 'Email sudah terdaftar.'],
            'username' => ['is_unique' => 'Username sudah digunakan.'],
            'confirm_password' => ['matches' => 'Konfirmasi password tidak cocok.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel    = new UserModel();
        $anggotaModel = new AnggotaModel();

        // Insert user
        $userId = $userModel->insert([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => 'user',
            'status'       => 1,
        ]);

        // Insert anggota
        $anggotaModel->insert([
            'id_user'   => $userId,
            'nim_nis'   => $this->request->getPost('nim_nis'),
            'alamat'    => $this->request->getPost('alamat'),
            'no_telp'   => $this->request->getPost('no_telp'),
            'created_at'=> date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function doForgotPassword()
    {
        // Demo placeholder
        return redirect()->back()->with('info', 'Jika email terdaftar, link reset akan dikirim. (Demo: fitur ini adalah placeholder)');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout.');
    }

    private function redirectByRole()
    {
        $role = $this->session->get('role');
        return $role === 'admin'
            ? redirect()->to('/admin/dashboard')
            : redirect()->to('/user/dashboard');
    }
}
