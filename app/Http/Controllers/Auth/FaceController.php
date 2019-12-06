<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Cloudder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaceController extends Controller
{
	private $album = 'UAM';
	private $albumKey = '49b75c7eb17a5df40ce7fa684bba8e23f61fdbd70503b71e92961e9644754734';
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
	            ],
	            'multipart' => [
	                [
	                	'name' => 'urls',
	                	'contents' => $urlResponse['secure_url'],
	                ],
	                [
	                	'name' => 'albumkey',
	                	'contents' => $this->albumKey,
	                ],
	                [
	                	'name' => 'album',
	                	'contents' => $this->album,
	                ],
	            ],
	            'verify' => false,
	            'http_errors' => false,
	            'synchronous' => false
	        ];
	        $client = new \GuzzleHttp\Client();
	        $apiRequest = $client->request('POST', 'https://lambda-face-recognition.p.rapidapi.com/recognize', $options);

	        $content = $apiRequest->getBody()->getContents();
	        $response = Cloudder::destroyImages([$id]);
	        return $content;
    	}
    }
    
    public function train(Request $request)
    {
    	$user = $this->findCreate($request->name, $request->email);
    	if ($user) {
	    	$response = Cloudder::upload($request->file);
	    	$id = Cloudder::getPublicId();
	    	$urlResponse = Cloudder::getResult();
	    	if (isset($urlResponse) && isset($urlResponse['secure_url'])) {
		    	$options = [
		            'headers' => [
						'x-rapidapi-host' => 'lambda-face-recognition.p.rapidapi.com',
						'x-rapidapi-key' => '90f17ea646msh014a212128373e9p12e3edjsnb0325db240c3',
		            ],
		            'multipart' => [
		                [
		                	'name' => 'urls',
		                	'contents' => $urlResponse['secure_url'],
		                ],
		                [
		                	'name' => 'albumkey',
		                	'contents' => $this->albumKey,
		                ],
		                [
		                	'name' => 'album',
		                	'contents' => $this->album,
		                ],
		                [
		                	'name' => 'entryid',
		                	'contents' => $user['entryid'],
		                ],
		            ],
		            'verify' => false,
		            'http_errors' => false,
		            'synchronous' => false
		        ];
		        $client = new \GuzzleHttp\Client();
		        $apiRequest = $client->request('POST', 'https://lambda-face-recognition.p.rapidapi.com/album_train', $options);

		        $content = $apiRequest->getBody()->getContents();
		        $response = Cloudder::destroyImages([$id]);
		        return $content;
	    	}
	    }
    }

    private function findCreate($name, $email) {
 
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://estructura-de-datos-ii.firebaseio.com/')
        ->create();
 
        $database   =   $firebase->getDatabase();
        $result = $database->getReference('faceauth/users')
	    // order the reference's children by the values in the field 'height'
	    ->orderByChild('email')
	    // returns all persons being exactly 1.98 (meters) tall
	    ->equalTo($email)
	    ->getSnapshot();

	    $items = $result->getValue();

	    if (empty($items)) {
	        $createPost    =   $database
	        ->getReference('faceauth/users')
	        ->push([
	            'name' =>  $name,
	            'entryid' => md5($email),
	            'email'  =>  $email
	 
	        ]);

	        return $createPost->getvalue();
	    }else{
	    	return collect($items)->first();
	    }
    }
}
