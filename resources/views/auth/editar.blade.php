@extends('layouts.app')

@section('htmlheader_title')
registro
@endsection

@section('main-content')
  <body class="hold-transition register-page">
@if(Session::has('flash_message'))
<div style="width:200px" class="alert alert-success alert-dismissable fade in">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Grandioso!</strong> {{Session::get('flash_message')}}
</div>
@endif
@if(Session::has('error_message'))
<div style="width:200px" class="alert alert-danger alert-dismissable fade in">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error! </strong> {{Session::get('error_message')}}
</div>
@endif
        <div class="register-logo">
            <label style="font-size:20px !important">EDITAR USUARIO</label>
        </div>


        <div class="register-box" style="margin-top:0px !important;margin-bottom:0px !important">
        <div class="register-box-body">
        @foreach($user as $usuario)
            <form action="{{ url('/editarusuario') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" value="0,0" name="posicion" id="posicion">
                <input type="hidden" name="id" value="{{$usuario->id}}">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="name" value="{{ $usuario->name }}"/>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Telefono" name="telefono" value="{{$usuario->telefono}}"/>
                    <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <label>Editar Email<input type="checkbox" id="editaremail" onchange="emails()" name="editaremail" value="1"></label>
                    <input type="hidden" name="" id="mailinp" value="{{ $usuario->email }}">
                    <input type="email" class="form-control" id="email" disabled placeholder="{{ trans('adminlte_lang::message.email') }}" name="email" value="{{ $usuario->email }}"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <label>Editar Contraseña<input type="checkbox" onchange="passed()" id="editarcontrasena" name="editarcontrasena" value="1"></label>
                    <input type="password" class="form-control" disabled id="pass" placeholder="{{ trans('adminlte_lang::message.password') }}" name="password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" disabled id="pass2" placeholder="{{ trans('adminlte_lang::message.retrypepassword') }}" name="password_confirmation"/>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="form-group">
                  <label for="">perfil de usuario</label>
                <select class="form-control select2" data-placeholder="Seleccione perfil de usuario" style="width:100%" tabindex="-1" aria-hidden="true"
                onchange="capturar()" id="id_perfil" name="id_perfil">
                    <option value="{{$usuario->id_perfil}}">{{validarlista($usuario->id_perfil,2)}}</option>
                    @foreach($perfiles as $perfil)
                      <option title="{{$perfil->descripcion}}" value="{{ $perfil->valor_lista}}">{{$perfil->valor_item}}</option>
                    @endforeach
                </select>
                </div>
                @if($usuario->id_perfil==1)
                <div class="form-group" id="bodega" style="display:none">
                  <label for="">Bodega</label>
                <select class="form-control select2" data-placeholder="Seleccione perfil de usuario" style="width:100%" tabindex="-1"
                id="id_bodega"  aria-hidden="true" name="bodega">
                <option value="">Seleccione una bodega</option>
                    @foreach($bodegas as $bodega)
                      <option title="{{$bodega->ciudad}}" value="{{$bodega->id}}">{{$bodega->nombre}}</option>
                    @endforeach
                </select>
                </div>
                @else
                <div class="form-group" id="bodega" style="display:block">
                  <label for="">Bodega</label>
                <select class="form-control select2" data-placeholder="Seleccione perfil de usuario" style="width:100%" tabindex="-1"
                id="id_bodega"  aria-hidden="true" name="bodega">
                <option value="{{$usuario->bodega_id}}">{{validarbodega($usuario->bodega_id)}}</option>
                    @foreach($bodegas as $bodega)
                      <option title="{{$bodega->ciudad}}" value="{{$bodega->id}}">{{$bodega->nombre}}</option>
                    @endforeach
                </select>
                </div>
                @endif

                <div class="row">
                      <div class="col-xs-10 col-xs-push-1" id="registrar">
                        @if (validarcertificado())

                        @else
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
                        @endif
                    </div>
                </div>
            </form>

    @endforeach

        </div><!-- /.form-box -->
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> {{ trans('adminlte_lang::message.someproblems') }}<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div><!-- /.register-box  if(bodega.options[bodega.selectedIndex].value>1)-->

</body>
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
                            document.getElementById('registrar').innerHTML='<button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>'; 
            }
        }
    }

    function MostrarPosition(position)
    {
        document.getElementById('posicion').value=position.coords.latitude+",   " + position.coords.longitude; 
        document.getElementById('registrar').innerHTML='<button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>'; 

    }
    function mostrarerrores(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
            alert("Para poder enviar el formulario debe proporcionar su ubicacion");
            break;
            case error.POSITION_UNAVAILABLE:
            alert("no se pudo acceder a la ubicacion"); 
            document.getElementById('registrar').innerHTML='<button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>'; 
            break;
            case error.TIMEOUT:
            alert("no se pudo acceder a la ubicacion"); 
            document.getElementById('registrar').innerHTML='<button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>'; 

            break;
            case error.UNKNOWN_ERROR:
            alert("no se pudo acceder a la ubicacion"); 
            document.getElementById('registrar').innerHTML='<button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>'; 

            break;
        }
    }
function capturar() {
  verbodega = document.getElementById("bodega");
  var bodega = document.getElementById('id_perfil');
  if(bodega.options[bodega.selectedIndex].value>1)
verbodega.style.display='block';
  else
verbodega.style.display='none';
}
function emails() {
    mailinp = document.getElementById("mailinp").value;
  mail = document.getElementById("editaremail").checked;
  if (mail) {
    document.getElementById('email').disabled=false;
    document.getElementById('email').value="";
  }else{
   document.getElementById('email').disabled=true;
    document.getElementById('email').value=mailinp;

  }
} 
function passed() {
  mail = document.getElementById("editarcontrasena").checked;
  if (mail) {
    document.getElementById('pass').disabled=false;
    document.getElementById('pass2').disabled=false;

  }else{
   document.getElementById('pass').disabled=true;
document.getElementById('pass2').disabled=false;
}
} 


</script>



@endsection
