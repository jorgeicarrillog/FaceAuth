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
    	$id = Cloudder::getPublicId();
    	$urlResponse = Cloudder::show($id);
    	if (isset($urlResponse) && isset($urlResponse['secure_url'])) {
	    	$options = [
	            'auth' => $credential, 
	            'headers' => [
					'x-rapidapi-host' => 'lambda-face-recognition.p.rapidapi.com',
					'x-rapidapi-key' => '90f17ea646msh014a212128373e9p12e3edjsnb0325db240c3',
					'content-type' => 'multipart/form-data'
	            ],
	            'form-data' => [
	                'urls' => $urlResponse['secure_url'],
	            ],
	            'verify' => false,
	            'http_errors' => false,
	            'synchronous' => false
	        ];
	        $client = new \GuzzleHttp\Client();
	        $response = $client->request('POST', 'https://lambda-face-recognition.p.rapidapi.com/detect', $options);

	        $content = json_decode($apiRequest->getBody()->getContents());
	        $response = Cloudder::destroyImages([$id]);
	        return $content;
    	}
    }
}
