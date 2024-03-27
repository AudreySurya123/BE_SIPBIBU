<?php

namespace App\Controllers;

use App\Models\DataIbuModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class DataIbuControl extends ResourceController
{
    use ResponseTrait;
    protected $format = 'json';

    public function index()
    {
        $model = new DataIbuModel();
        $data = $model->findAll();

        if (!empty($data)) {
            $response = [
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $data
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No data found',
                'data' => []
            ];
        }

        return $this->respond($response);
    }

    public function create()
    {
        $data = [
            'nama' => $this->request->getVar('nama'),
            'no_telp' => $this->request->getVar('no_telp'),
            'alamat' => $this->request->getVar('alamat'),
            'usia' => $this->request->getVar('usia'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
        ];

        $model = new DataIbuModel();
        $model->save($data);

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan',
            'data' => $data,
        ];

        return $this->respond($response);
    }

    public function update($id = null)
    {
        $model = new \App\Models\DataIbuModel();
        $data_ibu = $model->find($id);
        if ($data_ibu) {
            $data = [
                'nama' => $this->request->getVar('nama'),
                'no_telp' => $this->request->getVar('no_telp'),
                'alamat' => $this->request->getVar('alamat'),
                'usia' => $this->request->getVar('usia'),
                'email' => $this->request->getVar('email'),
                'password' => $this->request->getVar('password'),
            ];
            $proses = $model->update($id, $data); // Perubahan pada baris ini
            if ($proses) {
                $response = [
                    'status' => 200,
                    'messages' => 'Data berhasil diubah',
                    'data' => $data
                ];
            } else {
                $response = [
                    'status' => 402,
                    'messages' => 'Gagal diubah',
                ];
            }
            return $this->respond($response);
        }
        return $this->failNotFound('Data tidak ditemukan');
    }

    public function delete($id = null)
    {
        $model = new \App\Models\DataIbuModel();
        $data_ibu = $model->find($id);
        if ($data_ibu) {
            $proses = $model->delete($id);
            if ($proses) {
                $response = [
                    'status' => 200,
                    'messages' => 'Data berhasil dihapus',
                ];
            } else {
                $response = [
                    'status' => 402,
                    'messages' => 'Gagal menghapus data',
                ];
            }
            return $this->respond($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }
}

//     public function signup()
//     {
//         $data = [];

//         if ($this->request->getMethod() === 'post') {
//             // Validasi data yang dimasukkan oleh pengguna
//             $rules = [
//                 'nama' => 'required',
//                 'no_telp' => 'required',
//                 'alamat' => 'required',
//                 'usia' => 'required|numeric',
//                 'email' => 'required|valid_email|is_unique[data_ibu.email]',
//                 'password' => 'required'
//             ];

//             if (!$this->validate($rules)) {
//                 // Jika validasi gagal, kembalikan dengan pesan error
//                 return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
//             }

//             try {
//                 // Proses pendaftaran
//                 $model = new DataIbuModel();
//                 $data = [
//                     'nama' => $this->request->getPost('nama'),
//                     'no_telp' => $this->request->getPost('no_telp'),
//                     'alamat' => $this->request->getPost('alamat'),
//                     'usia' => $this->request->getPost('usia'),
//                     'email' => $this->request->getPost('email'),
//                     'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
//                 ];

//                 $model->insert($data);

//                 // Redirect ke halaman login setelah signup
//                 return redirect()->to('/login');
//             } catch (\Exception $e) {
//                 // Tangani kesalahan dan tampilkan pesan error
//                 return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
//             }
//         }

//         return view('signup_ibu', $data);
//     }

//     public function login()
//     {
//         if ($this->request->getMethod() === 'post') {
//             // Proses login
//             $email = $this->request->getPost('email');
//             $password = $this->request->getPost('password');

//             $model = new DataIbuModel();
//             $user = $model->where('email', $email)->first();

//             if ($user && password_verify($password, $user['password'])) {
//                 // Login berhasil, simpan data user di session
//                 session()->set('user', $user);
//                 // Redirect ke halaman dashboard
//                 return redirect()->to('/dashboard');
//             } else {
//                 // Login gagal, kembali ke halaman login dengan pesan error
//                 return redirect()->to('/login')->with('error', 'Email atau password salah');
//             }
//         }

//         return view('login_ibu');
//     }


//     public function dashboard()
//     {
//         if (!session()->has('user')) {
//             return redirect()->to('/login');
//         }

//         return view('dashboard_ibu');
//     }

//     public function logout()
//     {
//         session()->remove('user');
//         return redirect()->to('/login');
//     }

//     public function edit()
//     {
//         if (!session()->has('user')) {
//             return redirect()->to('/login');
//         }

//         $user = session()->get('user');

//         return view('edit_data_ibu', ['user' => $user]);
//     }

//     public function update()
//     {
//         if (!session()->has('user')) {
//             return redirect()->to('/login');
//         }

//         // Ambil data dari form
//         $nama = $this->request->getPost('nama');
//         $no_telp = $this->request->getPost('no_telp');
//         $alamat = $this->request->getPost('alamat');
//         $usia = $this->request->getPost('usia');

//         // Lakukan validasi data
//         $rules = [
//             'nama' => 'required',
//             'no_telp' => 'required',
//             'alamat' => 'required',
//             'usia' => 'required|numeric'
//         ];

//         if (!$this->validate($rules)) {
//             return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
//         }

//         // Ambil ID pengguna dari sesi
//         $userId = session()->get('user')['id_ibu'];

//         // Simpan perubahan data ke database
//         $model = new DataIbuModel();
//         $data = [
//             'nama' => $nama,
//             'no_telp' => $no_telp,
//             'alamat' => $alamat,
//             'usia' => $usia
//         ];

//         $model->update($userId, $data);

//         // Redirect ke halaman dashboard dengan pesan sukses
//         return redirect()->to('/dashboard')->with('success', 'Data berhasil diperbarui.');
//     }
// }
