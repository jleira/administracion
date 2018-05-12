@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')

<div class="row">
  @foreach($bodega as $bodega)
  <div class="col-lg-8">
    <form role="form" enctype="multipart/form-data" method="POST" action="{{ url('editarstorage') }}">
    {{ csrf_field() }}
    <input type="hidden" value="0,0" name="posicion" id="posicion">
    <input type="hidden" name="id" value="{{$bodega->id}}">
     <div class="form-group">
       <input class="form-control" type="text" name="nombre" value="{{$bodega->nombre}}">
     </div>
     <div class="form-group">
       <input class="form-control" type="text" name="ciudad" value="{{$bodega->ciudad}}">
     </div>
     <div class="form-group">
       <input class="form-control" type="text" name="direccion" value="{{$bodega->direccion}}">
     </div>
     <div class="form-group">
       <input class="form-control" type="text" name="telefono" value="{{$bodega->telefono}}">
     </div>
     <div class="box-footer" id="registrar">
          @if (validarcertificado())    
          @else
          <button type="submit" name="button" class="btn btn-primary">Editar</button>
          @endif                    
      </div>

</form>
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
            document.getElementById('registrar').innerHTML='  <button type="submit" class="btn btn-primary">Registrar</button>'; 
    }
}
}

function MostrarPosition(position)
  {
     document.getElementById('posicion').value=position.coords.latitude+",  " + position.coords.longitude; 
     document.getElementById('registrar').innerHTML=' <button type="submit" class="btn btn-primary">Editar</button>'; 

  }
function mostrarerrores(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("Para poder enviar el formulario debe proporcionar su ubicacion");
            break;
        case error.POSITION_UNAVAILABLE:
              
              document.getElementById('registrar').innerHTML='  <button type="submit" class="btn btn-primary">Registrar</button>'; 
            break;
        case error.TIMEOUT:
             
           document.getElementById('registrar').innerHTML=' <button type="submit" class="btn btn-primary">Editar</button>'; 

            break;
        case error.UNKNOWN_ERROR:
             
           document.getElementById('registrar').innerHTML=' <button type="submit" class="btn btn-primary">Editar</button>'; 

            break;
    }
}

  
</script>


@endsection
