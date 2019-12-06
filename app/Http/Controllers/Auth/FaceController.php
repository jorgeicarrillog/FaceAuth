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
    	Storage::put('public/avatars/1.jpg', base64_decode($request->file));
    	dd(Storage::url('public/avatars/1.jpg'));
			$options = [
                'auth' => $credential, 
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic '.$credential,
                ],
                'json' => [
                    'trsec' => true,
                    'parts' => $sms->parts,
                    'to' => $cellphones,
                    'from' => 'CR'.str_limit($sms->id,8,''),
                    'text' => $sms->text,
                    'dlr-url' => url('/api/sms/response?tel=%p&cp=%P&state=%d'),
                ],
                'verify' => false,
                'http_errors' => false,
                'synchronous' => false
            ];
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'https://gateway.plusmms.net/rest/message', $options);
        
    }
}
