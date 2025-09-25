<?php
<<<<<<< HEAD
namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        helper('url');
        return view('index', ['title' => 'Home']);
    }

    public function about()
    {
        helper('url');
        return view('about', ['title' => 'About']);
    }

    public function contact()
    {
        helper('url');
        return view('contact', ['title' => 'Contact']);
=======

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
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    }
}
