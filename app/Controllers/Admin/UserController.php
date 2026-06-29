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
        $data = [
            'title' => 'Pengguna',
            'users' => $this->userModel->findAll()
        ];
        return view('admin/users/v_index', $data);
    }

    public function show($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Pengguna',
            'user' => $user
        ];
        return view('admin/users/v_show', $data);
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggle($id)
    {
        $user = $this->userModel->find($id);
        if ($user) {
            $this->userModel->update($id, ['is_active' => !$user['is_active']]);
            return redirect()->back()->with('success', 'Status pengguna berhasil diubah.');
        }
        return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
    }
}
