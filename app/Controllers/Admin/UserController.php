<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AnggotaModel;

class UserController extends BaseController
{
    public function index()
    {
        $search  = $this->request->getGet('search') ?? '';
        $role    = $this->request->getGet('role') ?? '';
        $status  = $this->request->getGet('status') ?? '';
        $sort    = $this->request->getGet('sort') ?? 'id_user';
        $order   = $this->request->getGet('order') ?? 'desc';

        $allowed = ['id_user','nama_lengkap','username','email','role','status','last_login'];
        if (!in_array($sort, $allowed)) $sort = 'id_user';
        if (!in_array($order, ['asc','desc'])) $order = 'desc';

        $builder = $this->db->table('users');

        if ($search) {
            $builder->groupStart()
                ->like('nama_lengkap', $search)
                ->orLike('username', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }
        if ($role)   $builder->where('role', $role);
        if ($status !== '') $builder->where('status', $status);

        $users = $builder->orderBy($sort, $order)->get()->getResultArray();

        return $this->adminView('admin/users/index', compact('users', 'search', 'role', 'status', 'sort', 'order'));
    }

    public function create()
    {
        return $this->adminView('admin/users/form');
    }

    public function store()
    {
        $rules = [
            'nama_lengkap' => 'required|max_length[100]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'username'     => 'required|min_length[4]|is_unique[users.username]',
            'password'     => 'required|min_length[6]',
            'role'         => 'required|in_list[admin,user]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userId = $userModel->insert([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $this->request->getPost('role'),
            'status'       => 1,
        ]);

        if ($this->request->getPost('role') === 'user') {
            $anggotaModel = new AnggotaModel();
            $anggotaModel->insert([
                'id_user'    => $userId,
                'nim_nis'    => $this->request->getPost('nim_nis'),
                'alamat'     => $this->request->getPost('alamat'),
                'no_telp'    => $this->request->getPost('no_telp'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');

        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->where('id_user', $id)->first();

        return $this->adminView('admin/users/form', compact('user', 'anggota'));
    }

    public function update(int $id)
    {
        $rules = [
            'nama_lengkap' => 'required|max_length[100]',
            'email'        => "required|valid_email|is_unique[users.email,id_user,$id]",
            'username'     => "required|min_length[4]|is_unique[users.username,id_user,$id]",
            'role'         => 'required|in_list[admin,user]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'username'     => $this->request->getPost('username'),
            'role'         => $this->request->getPost('role'),
            'status'       => $this->request->getPost('status') ?? 1,
        ];

        $newPassword = $this->request->getPost('password');
        if ($newPassword) {
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $data);

        // Update/buat data anggota jika role user
        if ($this->request->getPost('role') === 'user') {
            $anggotaModel = new AnggotaModel();
            $existing = $anggotaModel->where('id_user', $id)->first();
            $anggotaData = [
                'nim_nis' => $this->request->getPost('nim_nis'),
                'alamat'  => $this->request->getPost('alamat'),
                'no_telp' => $this->request->getPost('no_telp'),
            ];
            if ($existing) {
                $anggotaModel->update($existing['id_anggota'], $anggotaData);
            } else {
                $anggotaModel->insert(array_merge($anggotaData, ['id_user' => $id, 'created_at' => date('Y-m-d H:i:s')]));
            }
        }

        return redirect()->to('/admin/users')->with('success', 'User berhasil diperbarui.');
    }

    public function toggle(int $id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');

        $newStatus = $user['status'] == 1 ? 0 : 1;
        $userModel->update($id, ['status' => $newStatus]);
        $msg = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->to('/admin/users')->with('success', "User berhasil $msg.");
    }

    public function delete(int $id)
    {
        if ($id == $this->session->get('id_user')) {
            return redirect()->to('/admin/users')->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        $userModel = new UserModel();
        $userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus.');
    }
}
