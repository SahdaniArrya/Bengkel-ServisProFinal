<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('admin/users/v_index', [
            'title' => 'Kelola Pengguna',
            'users' => $this->userModel->findAll()
        ]);
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('admin/users')->with('errors', ['Pengguna tidak ditemukan']);
        }

        return view('admin/users/v_edit', [
            'title' => 'Edit Pengguna',
            'user' => $user
        ]);
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'phone' => 'required',
            'role' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'role' => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($this->request->getPost('password')) {
            $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        $this->userModel->update($id, $userData);

        return redirect()->to('admin/users')->with('success', 'Data pengguna berhasil diupdate.');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);

        return redirect()->to('admin/users')->with('success', 'Pengguna berhasil dihapus.');
    }
}
