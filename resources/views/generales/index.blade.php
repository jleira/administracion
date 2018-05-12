@extends('layouts.app')

@section('htmlheader_title')
{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')

<div class="row">
	@if(Session::has('flash_message'))
	<div class="alert alert-success alert-dismissable fade in">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Grandioso!</strong> {{Session::get('flash_message')}}
	</div>
	@endif
	@if(Session::has('error_message'))
	<div class="alert alert-danger alert-dismissable fade in">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Lo sentimos! </strong> {{Session::get('error_message')}}
	</div>
	@endif

	<div class="col-lg-5">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Datos generales </h3>
			</div>
			<div class="box-body">

				<form role="form" enctype="multipart/form-data" method="POST" action="{{ url('cambiargenerales') }}">
					{{ csrf_field() }}

					<div class="form-group">
						<label for="exampleInput">Nombre</label>
						<input type="text" class="form-control" value="{{ old('nombre')}}" name="nombre" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('nombre') )
						<p style="color:red;margin:0px">{{ $errors->first('nombre') }}</p>
						@endif
					</div>
			
					<div class="" id="nit">
						<div class="form-group">
							<label for="exampleInput">Nit</label>
							<input type="number" class="form-control" value="{{ old('nit')}}" name="nit" id="exampleInputEmail1" placeholder="">
							@if ($errors->has('nit') )
							<p style="color:red;margin:0px">{{ $errors->first('nit') }}</p>
							@endif
						</div>

					</div>


					<div class="form-group">
						<label for="exampleInput">Telefono</label>
						<input type="number" class="form-control" value="{{ old('telefono')}}" name="telefono" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('telefono') )
						<p style="color:red;margin:0px">{{ $errors->first('telefono') }}</p>
						@endif
					</div>

					<div class="form-group">
						<label for="exampleInput">Celular</label>
						<input type="number" class="form-control" value="{{ old('celular')}}" name="celular" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('celular') )
						<p style="color:red;margin:0px">{{ $errors->first('celular') }}</p>
						@endif
					</div>
						
					<div class="form-group">
						<label for="exampleInput">Correo</label>
						<input type="text" class="form-control" value="{{ old('correo')}}" name="correo" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('correo') )
						<p style="color:red;margin:0px">{{ $errors->first('correo') }}</p>
						@endif
					</div>
					<div class="form-group">
						<label for="exampleInput">Direccion</label>
						<input type="text" class="form-control" value="{{ old('direccion')}}" name="direccion" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('direccion') )
						<p style="color:red;margin:0px">{{ $errors->first('direccion') }}</p>
						@endif
					</div>

	<div class="form-group">
						<label for="exampleInput">Sitioweb</label>
						<input type="text" class="form-control" value="{{ old('direccion')}}" name="direccion" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('direccion') )
						<p style="color:red;margin:0px">{{ $errors->first('direccion') }}</p>
						@endif
					</div>
					
					<div class="form-group">
						<label for="exampleInput">Color</label>
						<input type="color" name="color" value="">
					</div>
					<div class="form-group">
						<label for="exampleInput">logo</label>
						<input type="file" name="logo" value="">
					</div>
					<button type="submit" name="button">Enviar</button>
				</form>
			</div>
		</div>
	</div>
</div>



@endsection
