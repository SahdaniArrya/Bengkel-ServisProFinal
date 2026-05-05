<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));

        return view('v_profile', [
            'title' => 'My Profile',
            'user' => $user
        ]);
    }

    public function settings()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));

        return view('v_profile_settings', [
            'title' => 'Account Settings',
            'user' => $user
        ]);
    }

    public function help()
    {
        return view('v_help', [
            'title' => 'Need Help?'
        ]);
    }
}
