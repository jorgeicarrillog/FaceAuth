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

		<form class="login100-form validate-form text-center" id="formRegister" onsubmit="return false;">
			<span class="login100-form-title">
				Registar Estudiante
			</span>

			<div class="wrap-input100 validate-input" data-validate = "Email valido es obligatorio: ex@abc.xyz">
				<input class="input100" type="text" name="email" placeholder="Email" required>
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-envelope" aria-hidden="true"></i>
				</span>
			</div>

			<div class="wrap-input100 validate-input" data-validate = "Nombre es obligatorio">
				<input class="input100" type="text" name="name" placeholder="nombre" required>
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-lock" aria-hidden="true"></i>
				</span>
			</div>
			
			<div class="container-login100-form-btn">
				<button class="button" class="login100-form-btn" id="authBtn">
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
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
	        <button type="button" class="btn btn-primary" onClick="take_snapshot()">Capturar</button>
	      </div>
	  	</div>
	</div>
</div>
<div class="modal fade bd-example-modal-xl" id="camModalReg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-xl">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h3 class="modal-title" id="exampleModalLabel">Mira la camara</h3>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <div id="my_camera_2" class="m-auto rounded"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
	        <button type="button" class="btn btn-primary" onClick="take_snapshot(false)">Capturar</button>
	      </div>
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
			$('#authBtn').on('click',function() {
				var validator = $("#formRegister").validate({
				  rules: {
				    // simple rule, converted to {required:true}
				    name: "required",
				    // compound rule
				    email: {
				      required: true,
				      email: true
				    }
				  }
				});
				if (validator.form()) {
					$('#camModalReg').modal('show');
				}else{
					Swal.fire({
					  title: 'Formulario Erroneo',
					  text: 'Revise la información',
					  icon: 'warning'
					})
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
			$('#camModalReg').on('show.bs.modal', function (e) {
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
				Webcam.attach( '#my_camera_2' );
			})
		});
		$('#camModal,#camModalReg').on('hidden.bs.modal', function (e) {
			Webcam.reset();
		});
		function take_snapshot(auth = true) {
			// take snapshot and get image data
			Webcam.snap( function(data_uri) {
				//var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
				upServer(data_uri,auth)
				//uploadFile(data_uri)
				// display results in page
			} );
		}

		function upServer(raw_image_data,auth=false) {
			$('#camModal,#camModalReg').modal('hide');
			console.log(raw_image_data);
			var route = "upload";
			var form = new FormData();
			form.append("file", raw_image_data);
			if (!auth) {
				route = "train";
				form.append("name", $('[name="name"]').val());
				form.append("email", $('[name="email"]').val());
			}

			var settings = {
				"crossDomain": true,
				"url" : route,
				"method": "POST",
				"processData": false,
				"contentType": false,
				"data": form
			}
			Swal.fire({
	            html: '<h1><i class="fa fa-spin fa-spinner"></i></h1><p>Autenticando....<br>Espera no cierres la ventana. </p>',
	            allowOutsideClick:false,
	            allowEscapeKey:false,
	            allowEnterKey:false,
	            showConfirmButton:false,
	        });
			$.ajax(settings).done(function (response) {
				console.log(response);
				if (response && response.success && !auth) {
					Swal.fire({
						title: 'Registro exitoso',
						text: 'Ahora puedes autenticarte'
					});
				}else if(response && response.success && auth){
					Swal.fire({
						text: 'Autenticación exitosa',
						title: 'Hola '+response.user.name,
						imageUrl: raw_image_data,
						imageWidth:180
					});			
				}else{
					Swal.fire({
						title: 'Aun no estas registrado',
						text: 'Ingresa tus datos para reconocerte',
						icon: 'warning'
					});	
				}
			});
		}
	</script>
@endsection