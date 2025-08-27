<?php

namespace App\Controllers;

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
}