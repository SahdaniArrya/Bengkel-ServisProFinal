<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\UserModel;

class StaffController extends BaseController
{
    protected $staffModel;

    public function __construct()
    {
        $this->staffModel = new StaffModel();
    }

    public function index()
    {
        return view('admin/staff/v_index', [
            'title' => 'Kelola Staff',
            'staffs' => $this->staffModel->findAll()
        ]);
    }

    public function create()
    {
        return view('admin/staff/v_create', [
            'title' => 'Tambah Staff'
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'phone' => 'required',
            'specialization' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        // 1. Simpan ke tabel users
        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone' => $this->request->getPost('phone'),
            'role' => 'staff',
            'is_active' => 1
        ];
        
        $userModel->insert($userData);
        $userId = $userModel->getInsertID();

        // 2. Simpan ke tabel staff dengan ID yang sama dengan users
        $staffData = [
            'id' => $userId,
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'specialization' => $this->request->getPost('specialization'),
            'is_active' => 1
        ];

        $this->staffModel->insert($staffData);

        return redirect()->to('admin/staff')->with('success', 'Staff baru berhasil ditambahkan beserta akun loginnya.');
    }

    public function edit($id)
    {
        $staff = $this->staffModel->find($id);
        if (!$staff) {
            return redirect()->to('admin/staff')->with('errors', ['Staff tidak ditemukan']);
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        return view('admin/staff/v_edit', [
            'title' => 'Edit Staff',
            'staff' => $staff,
            'user' => $user
        ]);
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'phone' => 'required',
            'specialization' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($this->request->getPost('password')) {
            $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        $userModel->update($id, $userData);

        $staffData = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'specialization' => $this->request->getPost('specialization'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        $this->staffModel->update($id, $staffData);

        return redirect()->to('admin/staff')->with('success', 'Data staff berhasil diupdate.');
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        
        $this->staffModel->delete($id);
        $userModel->delete($id);

        return redirect()->to('admin/staff')->with('success', 'Staff berhasil dihapus.');
    }
}
