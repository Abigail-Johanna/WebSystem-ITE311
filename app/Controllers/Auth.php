<?php namespace App\Controllers;

use App\Models\UserModel; // Import the UserModel

class Auth extends BaseController
{
    /**
     * Displays the registration form and processes form submission.
     */
    public function register()
    {
        // Check if the form has been submitted (POST request).
        if ($this->request->is('post')) {
            // Step 4: Implement Registration Functionality
            $validationRules = [
                'name' => 'required|min_length[3]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]',
                'password_confirm' => 'required|matches[password]',
            ];

            if ($this->validate($validationRules)) {
                // Validation passed, proceed with user creation.
                $userModel = new UserModel();
                
                // Hash the password for security.
                $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

                // Create the user data array.
                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => $hashedPassword,
                    'role' => 'user', // Set a default role
                ];

                // Save the user data to the database.
                $userModel->save($userData);

                // On success, set a flash message and redirect to the login page.
                session()->setFlashdata('success', 'Registration successful! Please log in.');
                return redirect()->to('/login');
            } else {
                // Validation failed, reload the form with errors.
                return view('auth/register', ['validation' => $this->validator]);
            }
        }

        // Load the registration form view.
        return view('auth/register');
    }

    /**
     * Displays the login form and processes form submission.
     */
    public function login()
    {
        // Check for a POST request.
        if ($this->request->is('post')) {
            // Step 5: Implement Login Functionality
            $validationRules = [
                'email' => 'required|valid_email',
                'password' => 'required',
            ];

            if ($this->validate($validationRules)) {
                $userModel = new UserModel();
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                // Check the database for a user with the provided email.
                $user = $userModel->where('email', $email)->first();

                if ($user && password_verify($password, $user['password'])) {
                    // Password is correct, create a user session.
                    $userData = [
                        'user_id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true,
                    ];
                    session()->set($userData);

                    // Set a welcome message and redirect to the dashboard.
                    session()->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');
                    return redirect()->to('/dashboard');
                } else {
                    // Invalid credentials.
                    session()->setFlashdata('error', 'Invalid email or password.');
                    return view('auth/login');
                }
            } else {
                // Validation failed, reload the form with errors.
                return view('auth/login', ['validation' => $this->validator]);
            }
        }

        // Load the login form view.
        return view('auth/login');
    }

    /**
     * Destroys the user's session and redirects them.
     */
    public function logout()
    {
        // Step 6: Implement Session Management and Logout
        session()->destroy();
        return redirect()->to('/login');
    }

    /**
     * A protected page that only logged-in users can see.
     */
    public function dashboard()
    {
        // Step 6: Check if the user is logged in.
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'You must be logged in to view the dashboard.');
            return redirect()->to('/login');
        }
        
        // If logged in, load the dashboard view.
        return view('dashboard');
    }
}
