<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function  homePage()
    {
        return view('homepage');
    }

    public function loginPage()
    {
        return view('loginpage');
    }
    public function attendance()
    {
        return view('employee-management/employeeLogs');
    }
}
