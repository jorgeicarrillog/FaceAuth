<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Storage;
use Cloudder;
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
    	//$file = base64_decode($request->file);
    	$response = Cloudder::upload($request->file);
    	$id = Cloudder::getPublicId();
    	$urlResponse = Cloudder::getResult();
    	if (isset($urlResponse) && isset($urlResponse['secure_url'])) {
	    	$options = [
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
	        $apiRequest = $client->request('POST', 'https://lambda-face-recognition.p.rapidapi.com/detect', $options);

	        $content = $apiRequest->getBody()->getContents();
	        $response = Cloudder::destroyImages([$id]);
	        return $content;
    	}
    }
}
