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
					'x-rapidapi-key' => config('services.rapidapi.key'),
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
	        if ($content && $apiRequest->getStatusCode()==200) {
	        	$content = json_decode(stripslashes($content));
	        	if ($content->status=="success" 
	        		&& isset($content->photos) 
	        		&& isset($content->photos[0]->tags[0]->uids)) {
	        		$uid = collect($content->photos[0]->tags[0]->uids)->sortByDesc('confidence')->first();
	        		$items = $this->searchBy('entryid', $uid->prediction);
	        		if (count($items)>0) {
	        			$merged = array_merge(collect($items)->first(),(array) $uid);
		        		return response()->json(['success'=>true, 'user'=>$merged]);
	        		}
	        	}
	        }
		    return response()->json(['success'=>false, 'data'=>$content]);
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
						'x-rapidapi-key' => config('services.rapidapi.key'),
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
		        $this->rebuild();
		        if ($response && $apiRequest->getStatusCode()==200) {
		        	$content = json_decode(stripslashes($content),true);
		        	return response()->json(['success'=>true, 'data'=>$content]);
		        }
		        return response()->json(['success'=>false, 'data'=>$content]);
	    	}
	    }
    }

    private function searchBy($column, $key)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://estructura-de-datos-ii.firebaseio.com/')
        ->create();
 
        $database   =   $firebase->getDatabase();
        $result = $database->getReference('faceauth/users')
	    // order the reference's children by the values in the field 'height'
	    ->orderByChild($column)
	    // returns all persons being exactly 1.98 (meters) tall
	    ->equalTo($key)
	    ->getSnapshot();

	    $items = $result->getValue();

	    return $items;
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

	        $user = $createPost->getvalue();
	        $user['is_new'] = 1;

	        return $user;
	    }else{
	    	return collect($items)->first();
	    }
    }

    private function rebuild():void
    {
    	$options = [
            'headers' => [
				'x-rapidapi-host' => 'lambda-face-recognition.p.rapidapi.com',
				'x-rapidapi-key' => config('services.rapidapi.key'),
            ],
            'verify' => false,
            'http_errors' => false,
            'synchronous' => false
        ];
        $client = new \GuzzleHttp\Client();
        $apiRequest = $client->request('GET', 'https://lambda-face-recognition.p.rapidapi.com/album_rebuild?album='.$this->album.'&albumkey='.$this->albumKey, $options);

        $content = $apiRequest->getBody()->getContents();
    }
}
