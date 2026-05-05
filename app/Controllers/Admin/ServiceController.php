<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceModel;

class ServiceController extends BaseController
{
    protected $serviceModel;
    public function __construct() { $this->serviceModel = new ServiceModel(); }

    public function index()
    {
        return view('admin/services/v_index', [
            'title'    => 'Kelola Layanan',
            'services' => $this->serviceModel->orderBy('created_at','DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/services/v_form', ['title' => 'Tambah Layanan', 'service' => null]);
    }

    public function store()
    {
        $rules = ['name'=>'required|min_length[3]','price'=>'required|numeric','duration_min'=>'required|is_natural_no_zero','photo'=>'permit_empty|uploaded[photo]|max_size[photo,2048]|is_image[photo]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $photoName = null;
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move(ROOTPATH.'public/uploads/services', $photoName);
        }
        $this->serviceModel->insert(['name'=>$this->request->getPost('name'),'description'=>$this->request->getPost('description'),'price'=>$this->request->getPost('price'),'duration_min'=>$this->request->getPost('duration_min'),'photo'=>$photoName,'is_active'=>$this->request->getPost('is_active') ? 1 : 0]);
        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = $this->serviceModel->find($id);
        if (!$service) return redirect()->to('/admin/services')->with('error', 'Layanan tidak ditemukan.');
        return view('admin/services/v_form', ['title' => 'Edit Layanan', 'service' => $service]);
    }

    public function update($id)
    {
        $service = $this->serviceModel->find($id);
        if (!$service) return redirect()->to('/admin/services')->with('error', 'Layanan tidak ditemukan.');

        $rules = ['name'=>'required|min_length[3]','price'=>'required|numeric','duration_min'=>'required|is_natural_no_zero'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $updateData = ['name'=>$this->request->getPost('name'),'description'=>$this->request->getPost('description'),'price'=>$this->request->getPost('price'),'duration_min'=>$this->request->getPost('duration_min'),'is_active'=>$this->request->getPost('is_active') ? 1 : 0];
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            if ($service['photo'] && file_exists(ROOTPATH.'public/uploads/services/'.$service['photo'])) unlink(ROOTPATH.'public/uploads/services/'.$service['photo']);
            $photoName = $photo->getRandomName();
            $photo->move(ROOTPATH.'public/uploads/services', $photoName);
            $updateData['photo'] = $photoName;
        }
        $this->serviceModel->update($id, $updateData);
        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $service = $this->serviceModel->find($id);
        if (!$service) return redirect()->to('/admin/services')->with('error', 'Layanan tidak ditemukan.');
        if ($service['photo'] && file_exists(ROOTPATH.'public/uploads/services/'.$service['photo'])) unlink(ROOTPATH.'public/uploads/services/'.$service['photo']);
        $this->serviceModel->delete($id);
        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil dihapus.');
    }
}
