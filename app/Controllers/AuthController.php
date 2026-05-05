<?php
namespace App\Controllers;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;
    public function __construct() { $this->userModel = new UserModel(); }

    public function login() { return view('auth/v_login'); }

    public function loginProcess()
    {
        if (!$this->validate(['email'=>'required|valid_email','password'=>'required|min_length[6]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $user = $this->userModel->where('email', $this->request->getPost('email'))->where('is_active',1)->first();
        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }
        session()->set(['user_id'=>$user['id'],'name'=>$user['name'],'username'=>$user['name'],'email'=>$user['email'],'role'=>$user['role'],'logged_in'=>true]);
        return redirect()->to('/dashboard')->with('success', 'Selamat datang, '.$user['name'].'!');
    }

    public function register() { return view('auth/v_register'); }

    public function registerProcess()
    {
        $rules = ['name'=>'required|min_length[3]','email'=>'required|valid_email|is_unique[users.email]','phone'=>'required|min_length[10]','password'=>'required|min_length[6]','confirm_password'=>'required|matches[password]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->userModel->insert(['name'=>$this->request->getPost('name'),'email'=>$this->request->getPost('email'),'phone'=>$this->request->getPost('phone'),'password'=>password_hash($this->request->getPost('password'),PASSWORD_DEFAULT),'role'=>'pelanggan']);
        return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout() { session()->destroy(); return redirect()->to('/auth/login'); }

    public function dashboard()
    {
        switch (session()->get('role')) {
            case 'admin': return redirect()->to('/admin/dashboard');
            case 'staff': return redirect()->to('/staff/dashboard');
            default:      return redirect()->to('/pelanggan/booking');
        }
    }
}
