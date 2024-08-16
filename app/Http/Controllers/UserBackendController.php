<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserBackendController extends Controller
{
    function test(){
        return view('user-backend.index');
    }
    function page($page){
        return view('user-backend.pages.'.str_replace(".html",'',$page));
    }
}
