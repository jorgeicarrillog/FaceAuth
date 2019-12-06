<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaceController extends Controller
{
    public function login(Request $request)
    {
    	return view('login');
    }
    public function upload(Request $request)
    {
    	Storage::put('avatars/1.jpg', base64_decode($request->file));
    	dd(Storage::get('avatars/1.jpg'));
    }
}
