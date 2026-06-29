<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\UserModel;

class StaffController extends BaseController
{
    protected $staffModel;
    protected $userModel;

    public function __construct()
    {
        $this->staffModel = new StaffModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Kelola Staff',
            'staffs' => $this->staffModel->findAll()
        ];
        return view('admin/staff/v_index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Staff'
        ];
        return view('admin/staff/v_create', $data);
    }

    public function store()
    {
        // Validasi input
        $rules = [
            'name'           => 'required|min_length[3]',
            'email'          => 'required|valid_email|is_unique[users.email]',
            'phone'          => 'required|min_length[9]',
            'specialization' => 'required|min_length[3]',
            'password'       => 'required|min_length[6]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Create User for Login
        $this->userModel->save([
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone'     => $this->request->getPost('phone'),
            'role'      => 'staff',
            'is_active' => 1
        ]);

        // 2. Create Staff Data
        $this->staffModel->save([
            'name'           => $this->request->getPost('name'),
            'phone'          => $this->request->getPost('phone'),
            'specialization' => $this->request->getPost('specialization'),
            'is_active'      => 1
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menambahkan staff. Periksa kembali data yang diinput.');
        }

        return redirect()->to('/admin/staff')->with('success', 'Staff berhasil ditambahkan! Akun login sudah siap digunakan.');
    }

    public function edit($id)
    {
        $staff = $this->staffModel->find($id);
        if (!$staff) return redirect()->to('/admin/staff')->with('error', 'Data staff tidak ditemukan.');
        return view('admin/staff/v_edit', ['title' => 'Edit Staff', 'staff' => $staff]);
    }

    public function update($id)
    {
        $staff = $this->staffModel->find($id);
        if (!$staff) return redirect()->to('/admin/staff')->with('error', 'Data staff tidak ditemukan.');

        $rules = [
            'name'           => 'required|min_length[3]',
            'phone'          => 'required|min_length[9]',
            'specialization' => 'required|min_length[3]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->staffModel->update($id, [
            'name'           => $this->request->getPost('name'),
            'phone'          => $this->request->getPost('phone'),
            'specialization' => $this->request->getPost('specialization'),
        ]);

        return redirect()->to('/admin/staff')->with('success', 'Data staff berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->staffModel->delete($id);
        return redirect()->to('/admin/staff')->with('success', 'Staff berhasil dihapus.');
    }

    public function toggle($id)
    {
        $staff = $this->staffModel->find($id);
        if ($staff) {
            $this->staffModel->update($id, ['is_active' => !$staff['is_active']]);
            return redirect()->to('/admin/staff')->with('success', 'Status staff berhasil diubah.');
        }
        return redirect()->to('/admin/staff')->with('error', 'Staff tidak ditemukan.');
    }
}
