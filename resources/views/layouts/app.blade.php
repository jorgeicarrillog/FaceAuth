<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Login V1</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{secure_asset('assets/Login_v1/images/icons/favicon.ico')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!--<link rel="stylesheet" type="text/css" href="{{secure_asset('assets/Login_v1/vendor/bootstrap/css/bootstrap.min.css')}}">-->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{secure_asset('assets/Login_v1/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{secure_asset('assets/Login_v1/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{secure_asset('assets/Login_v1/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{secure_asset('assets/Login_v1/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{secure_asset('assets/Login_v1/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{secure_asset('assets/Login_v1/css/main.css')}}">
<!--===============================================================================================-->
<script src="assets/jquery.min.js"></script>
@yield('head')
</head>
<body>
	
	<div class="limiter">
		@yield('content')
	</div>
	
	<script src="//code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	
<!--===============================================================================================-->
	<script src="{{secure_asset('assets/Login_v1/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{secure_asset('assets/Login_v1/vendor/tilt/tilt.jquery.min.js')}}"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<script src="//cdn.jsdelivr.net/npm/promise-polyfill"></script>
	<script type="text/javascript" src="assets/webcamjs/webcam.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="{{secure_asset('assets/Login_v1/js/main.js')}}"></script>
	@yield('scripts')
	<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="//www.gstatic.com/firebasejs/7.5.1/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     //firebase.google.com/docs/web/setup#available-libraries -->
<script src="//www.gstatic.com/firebasejs/7.5.1/firebase-analytics.js"></script>

<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyCUsGp32v_NGDZMsh5-x9eVjVPLxZ3Nqcw",
    authDomain: "estructura-de-datos-ii.firebaseapp.com",
    databaseURL: "//estructura-de-datos-ii.firebaseio.com",
    projectId: "estructura-de-datos-ii",
    storageBucket: "estructura-de-datos-ii.appspot.com",
    messagingSenderId: "965550217093",
    appId: "1:965550217093:web:54f097e1ce5f596a4696ec",
    measurementId: "G-MP4PKFG6DW"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
</script>
</body>
</html>