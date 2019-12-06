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
    	$file = base64_decode($request->file);
    	$response = Cloudder::upload($file);
    	dd(Cloudder::show(Cloudder::getPublicId()));
    }
}
