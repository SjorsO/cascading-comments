<?php

namespace App\Http\Controllers;

use App\Models\Repository;

class HomeController
{
    public function index()
    {
        return view('home', [
            'repositories' => Repository::get(),
        ]);
    }
}
