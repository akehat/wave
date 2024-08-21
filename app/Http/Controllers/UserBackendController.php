<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserBackendController extends Controller
{
    function portal(){
        return view('user-backend.index');
    }
    function contact(){
        return view('user-backend.contact');
    }
    function howto(){
        return view('user-backend.howto');
    }
    function menu(){
        return view('user-backend.menu');
    }
    function site(){
        return view('user-backend.site');
    }
    function general(){
        return view('user-backend.general');
    }
    function save_brokers(Request $request){
        return redirect()->back();
    }
    function page($page){
        return view('user-backend.pages.'.str_replace(".html",'',$page));
    }
}
