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
 <div class="col-md-4">
       <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Editar Categoria</h3>
          </div>
                <!-- /.box-header -->
                <!-- form start -->
          <form role="form" enctype="multipart/form-data" method="POST" action="{{ url('editarcategoria') }}">
          {{ csrf_field() }}
          <input type="hidden" value="0,0" name="posicion" id="posicion">
          <div class="box-body">
						@foreach ($categorias as $categoria)
						<input type="hidden" name="id" value="{{$categoria->valor_lista}}">
            <div class="form-group">
              <label for="exampleInput">Nombre</label>
                      <input type="text" class="form-control" value="{{$categoria->valor_item}}" name="nombre" id="exampleInputEmail1" placeholder="">
											@if ($errors->has('nombre') )
						      		<p style="color:red;margin:0px">{{ $errors->first('nombre') }}</p>
						      		@endif
						</div>
						<div class="form-group">
							<label for="exampleInput">Descripcion</label>
							<input type="text" class="form-control" value="{{$categoria->descripcion}}" name="descripcion" id="exampleInputEmail1" placeholder="">
							@if ($errors->has('ciudad') )
							<p style="color:red;margin:0px">{{ $errors->first('ciudad') }}</p>
							@endif
						</div>

						<div class="form-group">
							<label for="exampleInput">Codigo</label>
							<input type="number" class="form-control" name="codigo" value="{{$categoria->valor_lista}}" id="exampleInputEmail1" placeholder="" disabled>
							@if ($errors->has('codigo') )
							<p style="color:red;margin:0px">{{ $errors->first('codigo') }}</p>
							@endif
						</div>



                  <div class="box-footer" id="registrar">
                  	@if (validarcertificado())
					
					@else
					<button type="submit" class="btn btn-primary">Editar</button>
					@endif
                    
                  </div>
                </form>
							@endforeach
              </div>
            </div>
</div>



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
            alert("no se pudo acceder a la ubicacion"); 
              document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>'; 
            break;
        case error.TIMEOUT:
           alert("no se pudo acceder a la ubicacion"); 
           document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Editar</button>'; 

            break;
        case error.UNKNOWN_ERROR:
           alert("no se pudo acceder a la ubicacion"); 
           document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Editar</button>'; 

            break;
    }
}
</script>
@endsection
