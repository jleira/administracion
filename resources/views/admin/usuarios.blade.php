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
<div class="col-lg-12">
  <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title">Usuarios</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table id="usuarios" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>id</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Bodega</th>
                    <th>contacto</th>
                    <th>Editar</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($usuarios as $usuario)
                    <tr>
                      <td>{{$usuario->id}}</td>
                      <td>{{$usuario->name}}</td>
                      <td>{{$usuario->email}}</td>
                      <td>{{validarlista($usuario->id_perfil,2)}}</td>
                      @if($usuario->bodega_id>0)
                      <td>{{validarbodega($usuario->bodega_id)}}</td>
                      @else
                      <td>Todas</td>
                      @endif
                      <td>{{$usuario->telefono}}</td>
                      <td><a href="{{url('admin/editarusuario/'.$usuario->id)}}">editar</a></td>
                    </tr>
                  @endforeach

                  </tbody>
                  <tfoot>
                  <tr>
                                        <th>id</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Bodega</th>
                    <th>contacto</th>
                    <th>Editar</th>


                   </tr>
                   </tfoot>
                   </table>
                 </div>
                 </div>
               </div>


</div>
<script type="text/javascript">
$(function () {
$("#usuarios").DataTable({
"language": {
"lengthMenu": "Mostrar _MENU_ usuarios por pagina",
"zeroRecords": "No hay usuarios registrados",
"info": "Pagina _PAGE_ de _PAGES_",
"infoEmpty": "",
"infoFiltered": "(Filtrado de _MAX_ bodegas )",
        "search":'',
        "searchPlaceholder": "Buscar",
"paginate": {
"first":      "Primero",
"last":       "Ultimo",
"next":       "Siguiente",
"previous":   "Anterior"
}
}
});
});

</script>
@endsection
