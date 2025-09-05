<?php

namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
    public function index(): string
    {
        $data['title'] = 'Home';
        return view('index', $data);
    }

    public function about(): string
    {
        $data['title'] = 'About';
        return view('about', $data);
    }

    public function contact(): string
    {
        $data['title'] = 'Contact';
        return view('contact', $data);
    }

    public function login()
    {
        $data['title'] = 'Login';

        if ($this->request->getMethod() === 'post') {
            $session = session();
            $model   = new UserModel();

            $email    = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $user = $model->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                $session->set([
                    'user_id'    => $user['id'],
                    'name'       => $user['name'],
                    'email'      => $user['email'],
                    'role'       => $user['role'], 
                    'isLoggedIn' => true,
                ]);
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Invalid login credentials.');
                return redirect()->to('/login');
            }
        }

        return view('auth/login', $data); 
    }

    public function register()
    {
        $data['title'] = 'Register';

        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $rules = [
                'name'             => 'required|min_length[3]',
                'email'            => 'required|valid_email|is_unique[users.email]',
                'password'         => 'required|min_length[6]',
                'password_confirm' => 'matches[password]',
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('auth/register', $data); 
            }

            $model = new UserModel();
            $model->save([
                'name'     => $this->request->getVar('name'),
                'email'    => $this->request->getVar('email'),
                'password' => $this->request->getPost('password'),
                'role'     => 'user', 
            ]);

            session()->setFlashdata('success', 'Registration successful! You can now log in.');
            return redirect()->to('/login');
        }

        return view('auth/register', $data); 
    }

    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['title'] = 'Dashboard';
        return view('dashboard', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
