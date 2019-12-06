@extends('layouts.app')
@section('style')
<style type="text/css">	
.progress-bar{
  width: 200px;
  position: relative;
  height: 8px;
  margin-top: 4px;
}
.progress-bar .progress{
  height: 8px;
  background-color: #ff0000;
  width: 0;
}
</style>
@endsection
@section('content')
<div class="container-login100">
	<div class="wrap-login100 text-center justify-content-betewen align-items-center">
		<div class="w-100 text-center">
			<img src="{{asset('assets/logo-uam.png')}}" class="text-center" alt="UAM">
		</div>
		<div class="login100-pic js-tilt" data-tilt>
			<div id="results" >
			<img src="{{asset('assets/Login_v1/images/img-01.png')}}" alt="IMG" width="80%">
			</div>
			<div class="container-login100-form-btn">
				<button class="login100-form-btn" data-toggle="modal" data-target="#camModal">
					Autenticarme
				</button>
			</div>
		</div>

		<form class="login100-form validate-form text-center">
			<span class="login100-form-title">
				Registar Estudiante
			</span>

			<div class="wrap-input100 validate-input" data-validate = "Email valido es obligatorio: ex@abc.xyz">
				<input class="input100" type="text" name="email" placeholder="Email">
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-envelope" aria-hidden="true"></i>
				</span>
			</div>

			<div class="wrap-input100 validate-input" data-validate = "Nombre es obligatorio">
				<input class="input100" type="text" name="name" placeholder="nombre">
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-lock" aria-hidden="true"></i>
				</span>
			</div>
			
			<div class="container-login100-form-btn">
				<button class="login100-form-btn">
					Registrarme
				</button>
			</div>
		</form>
	</div>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="camModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Mira la camara</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="my_camera" class="m-auto rounded"></div>
		  <div class="progress-bar" id="progress-bar">
		    <div class="progress" id="progress"></div>
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onClick="take_snapshot()">Capturar</button>
      </div>
  </div>
</div>
<!-- Configure a few settings and attach camera -->
	<script language="JavaScript">
		$(document).ready(function() {
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
			$('#camModal').on('show.bs.modal', function (e) {
				Webcam.set({
					width: 1000,
					height: 640,
					image_format: 'jpeg',
					jpeg_quality: 90,
					constraints: {
						width: { exact: 1000 },
						height: { exact: 640 }
					}
				});
				Webcam.attach( '#my_camera' );
			})
		});
		$('#camModal').on('hidden.bs.modal', function (e) {
			Webcam.reset();
		});
		function take_snapshot() {
			// take snapshot and get image data
			Webcam.snap( function(data_uri) {
				//var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
				upServer(data_uri)
				//uploadFile(data_uri)
				// display results in page
				document.getElementById('results').innerHTML = '<img src="'+data_uri+'" class="rounded-circle"/>';
				$('#camModal').modal('hide');
			} );
		}

		function upServer(raw_image_data) {
			console.log(raw_image_data);
			//upload file 5da8025b01842edd8614d6a96e3e99b0
			var form = new FormData();
			form.append("applinkname", "UAM");
			form.append("formname", "IA-AUTH");
			form.append("fieldname", "AUTH");
			form.append("recordId", 'IA'+Date.now());
			form.append("filename", 'IA'+Date.now());
			form.append("file", raw_image_data);

			var settings = {
				"crossDomain": true,
				//"url": "https://creator.zoho.com/api/xml/fileupload/scope=creatorapi&authtoken=5da8025b01842edd8614d6a96e3e99b0",
				"url" : "{{secure_url('/upload')}}",
				"method": "POST",
				"processData": false,
				"contentType": false,
				"data": form
			}

			$.ajax(settings).done(function (response) {
				console.log(response);
			});
		}

		function upRapi(img){
			var form = new FormData();
			form.append("urls", img);

			var settings = {
				"async": true,
				"crossDomain": true,
				"url": "https://lambda-face-recognition.p.rapidapi.com/detect",
				"method": "POST",
				"headers": {
					"x-rapidapi-host": "lambda-face-recognition.p.rapidapi.com",
					"x-rapidapi-key": "90f17ea646msh014a212128373e9p12e3edjsnb0325db240c3",
					"content-type": "multipart/form-data"
				},
				"processData": false,
				"contentType": false,
				"mimeType": "multipart/form-data",
				"data": form
			}

			$.ajax(settings).done(function (response) {
				console.log(response);
			});
		}

		const cloudName = 'elukas';
		const unsignedUploadPreset = 'testUpload';

		// *********** Upload file to Cloudinary ******************** //
		function uploadFile(file) {
		  var url = `https://api.cloudinary.com/v1_1/${cloudName}/upload`;
		  var xhr = new XMLHttpRequest();
		  var fd = new FormData();
		  xhr.open('POST', url, true);
		  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

		  // Reset the upload progress bar
		   document.getElementById('progress').style.width = 0;
		  
		  // Update progress (can be used to show progress indicator)
		  xhr.upload.addEventListener("progress", function(e) {
		    var progress = Math.round((e.loaded * 100.0) / e.total);
		    document.getElementById('progress').style.width = progress + "%";

		    console.log(`fileuploadprogress data.loaded: ${e.loaded},
		  data.total: ${e.total}`);
		  });

		  xhr.onreadystatechange = function(e) {
		    if (xhr.readyState == 4 && xhr.status == 200) {
		      // File uploaded successfully
		      var response = JSON.parse(xhr.responseText);
		      // https://res.cloudinary.com/cloudName/image/upload/v1483481128/public_id.jpg
		      var url = response.secure_url;
		      // Create a thumbnail of the uploaded image, with 150px width
		      var tokens = url.split('/');
		      tokens.splice(-2, 0, 'w_150,c_scale');
		      var img = new Image(); // HTML5 Constructor
		      img.src = tokens.join('/');
		      img.alt = response.public_id;
		      document.getElementById('gallery').appendChild(img);
		    }
		  };

		  fd.append('upload_preset', unsignedUploadPreset);
		  fd.append('tags', 'browser_upload'); // Optional - add tag for image admin in Cloudinary
		  fd.append('file', file);
		  xhr.send(fd);
		}
	</script>
@endsection