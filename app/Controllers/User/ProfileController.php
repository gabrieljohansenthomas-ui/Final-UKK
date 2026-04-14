<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AnggotaModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $idUser = $this->session->get('id_user');
        $userModel = new UserModel();
        $anggotaModel = new AnggotaModel();

        $user    = $userModel->find($idUser);
        $anggota = $anggotaModel->where('id_user', $idUser)->first();

        return $this->userView('user/profile/index', compact('user', 'anggota'));
    }

    public function update()
    {
        $id = $this->session->get('id_user');
        $rules = [
            'nama_lengkap' => 'required|max_length[100]',
            'email'        => "required|valid_email|is_unique[users.email,id_user,$id]",
            'foto_profil'  => 'permit_empty|uploaded[foto_profil]|max_size[foto_profil,2048]|mime_in[foto_profil,image/jpg,image/jpeg,image/png,image/gif]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel    = new UserModel();
        $anggotaModel = new AnggotaModel();
        $user         = $userModel->find($id);

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
        ];
        $newPw = $this->request->getPost('password');
        if ($newPw) $data['password'] = password_hash($newPw, PASSWORD_DEFAULT);

        $file = $this->request->getFile('foto_profil');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($user['foto_profil'] && $user['foto_profil'] !== 'default.png') {
                $old = ROOTPATH . 'public/uploads/profiles/' . $user['foto_profil'];
                if (file_exists($old)) unlink($old);
            }
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/profiles', $newName);
            $data['foto_profil'] = $newName;
        }

        $userModel->update($id, $data);

        // Update anggota
        $anggota = $anggotaModel->where('id_user', $id)->first();
        $anggotaData = [
            'nim_nis' => $this->request->getPost('nim_nis'),
            'alamat'  => $this->request->getPost('alamat'),
            'no_telp' => $this->request->getPost('no_telp'),
        ];
        if ($anggota) {
            $anggotaModel->update($anggota['id_anggota'], $anggotaData);
        }

        // Update session
        $this->session->set('nama_lengkap', $data['nama_lengkap']);
        $this->session->set('email', $data['email']);
        if (isset($data['foto_profil'])) $this->session->set('foto_profil', $data['foto_profil']);

        return redirect()->to('/user/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
