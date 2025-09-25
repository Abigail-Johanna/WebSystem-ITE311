<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $helpers = ['form', 'url'];

    /**
     * Login page + authentication
     */
    public function login()
    {
        // If already logged in, go to dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        if ($this->request->getMethod() === 'GET') {
            return view('auth/login');
        }

        // POST
        if (! $this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->findUserByEmail($this->request->getPost('email'));

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Save session
        session()->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
            'user_role' => $user['role'],
            'logged_in' => true,
        ]);

        return redirect()->to('/dashboard');
    }

    /**
     * Registration
     */
    public function register()
    {
        if ($this->request->getMethod() === 'GET') {
            return view('auth/register');
        }

        if (! $this->validate([
            'name'             => 'required|min_length[3]|max_length[255]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            // ✔ ENUM roles
            'role'             => 'required|in_list[admin,teacher,student]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userModel->createAccount([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
        ]);

        return redirect()->to('/auth/login')->with('success', 'Account created successfully. You can now log in.');
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'You have been logged out.');
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please log in first.');
        }

        $userRole = session()->get('user_role');
        $userId   = session()->get('user_id');

        $userModel = new UserModel();
        $stats = $userModel->getDashboardStats($userRole, $userId);

        $data = array_merge([
            'user_name' => session()->get('user_name'),
            'user_role' => $userRole,
        ], $stats);

        return view('dashboard/index', $data);
    }
}
