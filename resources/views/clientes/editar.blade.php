@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')

  <div class="row">

 <div class="col-lg-12">
			@foreach($cliente as $cliente )
       <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Editar Cliente</h3>
          </div>
          <form role="form" enctype="multipart/form-data" method="POST" action="{{ url('editarcliente') }}">
          {{ csrf_field() }}
					<input type="hidden" name="id" value="{{$cliente->id}}">
					<input type="hidden" value="0,0" name="posicion" id="posicion">

          <div class="box-body">
						<div class="form-group">
							<label for="exampleInput">Clase de Cliente</label>
							<select class="form-control" name="clase" id="clase" onchange="mostrarnit()">
								<option value="{{$cliente->clase}}">{{validarlista($cliente->clase,5)}}</option>
								@foreach($clase as $clase )
								<option value="{{$clase->valor_lista}}">{{$clase->valor_item}}</option>
								@endforeach
							</select>
						</div>

            <div class="form-group">
              <label for="exampleInput">Nombre</label>
                      <input type="text" class="form-control" value="{{$cliente->nombre}}" name="nombre" id="exampleInputEmail1" placeholder="">
											@if ($errors->has('nombre') )
						      		<p style="color:red;margin:0px">{{ $errors->first('nombre') }}</p>
						      		@endif
						</div>

						@if($cliente->clase==1)
						<div class="" id="persona" style="display:none">
							<div class="form-group">
								<label for="exampleInput">Apellido</label>
												<input type="text" class="form-control" value="{{$cliente->apellido}}" name="apellido" id="exampleInputEmail1" placeholder="">
												@if ($errors->has('apellido') )
												<p style="color:red;margin:0px">{{ $errors->first('apellido') }}</p>
												@endif
							</div>

							<div class="form-group">
								<label for="exampleInput">Cedula</label>
												<input type="number" class="form-control" value="{{$cliente->cedula}}" name="cedula" id="exampleInputEmail1" placeholder="">
												@if ($errors->has('cedula') )
												<p style="color:red;margin:0px">{{ $errors->first('cedula') }}</p>
												@endif
							</div>
						</div>

						<div class="" id="nit">
							<div class="form-group">
								<label for="exampleInput">Nit</label>
												<input type="number" class="form-control" value="{{$cliente->nit}}" name="nit" id="exampleInputEmail1" placeholder="">
												@if ($errors->has('nit') )
												<p style="color:red;margin:0px">{{ $errors->first('nit') }}</p>
												@endif
							</div>

						</div>
						@else

						<div class="" id="persona" style="display:block">
							<div class="form-group">
								<label for="exampleInput">Apellido</label>
												<input type="text" class="form-control" value="{{$cliente->apellido}}" name="apellido" id="exampleInputEmail1" placeholder="">
												@if ($errors->has('apellido') )
												<p style="color:red;margin:0px">{{ $errors->first('apellido') }}</p>
												@endif
							</div>

							<div class="form-group">
								<label for="exampleInput">Cedula</label>
												<input type="number" class="form-control" value="{{$cliente->cedula}}" name="cedula" id="exampleInputEmail1" placeholder="">
												@if ($errors->has('cedula') )
												<p style="color:red;margin:0px">{{ $errors->first('cedula') }}</p>
												@endif
							</div>
						</div>

						<div class="" id="nit" style="display:none">
							<div class="form-group">
								<label for="exampleInput">Nit</label>
												<input type="number" class="form-control" value="{{$cliente->nit}}" name="nit" id="exampleInputEmail1" placeholder="">
												@if ($errors->has('nit') )
												<p style="color:red;margin:0px">{{ $errors->first('nit') }}</p>
												@endif
							</div>

						</div>


						@endif




						<div class="form-group">
              <label for="exampleInput">Telefono</label>
                      <input type="number" class="form-control" value="{{$cliente->telefono}}" name="telefono" id="exampleInputEmail1" placeholder="">
											@if ($errors->has('telefono') )
						      		<p style="color:red;margin:0px">{{ $errors->first('telefono') }}</p>
						      		@endif
						</div>
						<div class="form-group">
											@if ($cliente->credito==1)
											<label><input type="checkbox" name="credito" checked="true" value="1">
												Cliente dispone de credito
											</label>
											@else
											<label><input type="checkbox" name="credito" value="1">
												Cliente dispone de credito
											</label>
											@endif

						</div>
						<div class="form-group">
							<label for="exampleInput">Celular</label>
											<input type="number" class="form-control" value="{{$cliente->celular}}" name="celular" id="exampleInputEmail1" placeholder="">
											@if ($errors->has('celular') )
											<p style="color:red;margin:0px">{{ $errors->first('celular') }}</p>
											@endif
						</div>
						<div class="form-group">
							<label for="exampleInput">Correo</label>
											<input type="text" class="form-control" value="{{$cliente->correo}}" name="correo" id="exampleInputEmail1" placeholder="">
											@if ($errors->has('correo') )
											<p style="color:red;margin:0px">{{ $errors->first('correo') }}</p>
											@endif
						</div>
						<div class="form-group">
							<label for="exampleInput">Direccion</label>
											<input type="text" class="form-control" value="{{$cliente->direccion}}" name="direccion" id="exampleInputEmail1" placeholder="">
											@if ($errors->has('direccion') )
											<p style="color:red;margin:0px">{{ $errors->first('direccion') }}</p>
											@endif
						</div>
						<div class="form-group">
							<label for="exampleInput">Tipo de cliente</label>
							<select class="form-control" name="tipo">
								<option value="{{$cliente->tipo}}">{{validarlista($cliente->clase,3)}}</option>
								@foreach($tipos as $tipo )
								<option value="{{$tipo->valor_lista}}">{{$tipo->valor_item}}</option>
								@endforeach
							</select>
						</div>

                  <div class="box-footer" id="registrar">
                  	@if (validarcertificado())
					
					@else
					<button type="submit" class="btn btn-primary">Editar</button>
					@endif
                    
                  </div>
                </form>
              </div>
            </div>
</div>

@endforeach

</div>

<script type="text/javascript">

window.onload = function() {

if (window.location.protocol != "https:") {

}else{

  if (navigator.geolocation)
    {    
	   navigator.geolocation.getCurrentPosition(MostrarPosition, mostrarerrores);
    }
  else
    {
       alert("Geolocalizacion no soportada por el navegador");
       			document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>'; 
    }
}
}

function MostrarPosition(position)
  {
     document.getElementById('posicion').value=position.coords.latitude+",	" + position.coords.longitude; 
     document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Editar</button>'; 

  }
function mostrarerrores(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("Para poder enviar el formulario debe proporcionar su ubicacion");
            break;
        case error.POSITION_UNAVAILABLE:
              
              document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>'; 
            break;
        case error.TIMEOUT:
             
           document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Editar</button>'; 

            break;
        case error.UNKNOWN_ERROR:
             
           document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Editar</button>'; 

            break;
    }
}



function mostrarnit() {
	 var categoria=document.getElementById("clase").value;
	 if (categoria==2) {
	 		document.getElementById('persona').style.display = "block";
			document.getElementById('nit').style.display = "none";		
	 }
	 else {
		 document.getElementById('persona').style.display = "none";
 		document.getElementById('nit').style.display = "block";
	 }
}

</script>
@endsection
