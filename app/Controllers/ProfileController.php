<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id') ?? session()->get('id'); // Bergantung pada nama key di session
        
        $data = [
            'title' => 'Profile',
            'user'  => $this->userModel->find($userId)
        ];
        return view('profile/v_index', $data);
    }

    public function settings()
    {
        $userId = session()->get('user_id') ?? session()->get('id');
        
        $data = [
            'title' => 'Profile/settings',
            'user'  => $this->userModel->find($userId)
        ];
        return view('profile/v_settings', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id') ?? session()->get('id');
        
        $updateData = [
            'name'  => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
        ];

        $this->userModel->update($userId, $updateData);

        // Update session
        session()->set('username', $updateData['name']);
        
        return redirect()->to('/profile')->with('success', 'Profile berhasil diperbarui.');
    }

    public function help()
    {
        $data = [
            'title' => 'Help & Bantuan'
        ];
        return view('profile/v_help', $data);
    }
}
