@extends('layouts.app')

@section('htmlheader_title')
{{ trans('adminlte_lang::message.home') }}
@endsection

@section('main-content')

<div class="row">
  <div class="col-lg-6">
    <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Reservas</h3>
      </div>
      <div class="box-body">
        <table  class="table PEDIDOS  table-bordered table-striped">
<thead>
            <tr>
              <th>Id</th>
              <th>fecha</th>
              <th>detalle</th>
            </tr>
          </thead>
          
             <tbody>
            @foreach ($pedidos as $pedido)
            <tr>
              <td>{{$pedido->identificador}}</td>
              <td>{{$pedido->fecha}}</td>
              <td><a target="_blank" href="{{url('/seguimientos/'.$pedido->clase.'/'.$pedido->identificador)}}">mas</a></td>
            </tr>
            @endforeach
          </tbody>
      </table>
      </div>

</div>
</div>
  <div class="col-lg-6">
    <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Productos</h3>
      </div>
      <div class="box-body">
        <table  class="table PEDIDOS  table-bordered table-striped">
<thead>
            <tr>
              <th>Id</th>
              <th>fecha</th>
              <th>detalle</th>
            </tr>
          </thead>
          
             <tbody>
            @foreach ($producto as $pedido)
            <tr>
              <td>{{validarproducto($pedido->identificador)}}</td>
              <td>{{$pedido->fecha}}</td>
              <td><a target="_blank" href="{{url('/seguimientos/'.$pedido->clase.'/'.$pedido->identificador)}}">mas</a></td>
            </tr>
            @endforeach
          </tbody>
      </table>
      </div>

</div>
</div>
 <div class="col-lg-6">
    <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Movimientos</h3>
      </div>
      <div class="box-body">
        <table  class="table PEDIDOS  table-bordered table-striped">
<thead>
            <tr>
              <th>Id</th>
              <th>fecha</th>
              <th>detalle</th>
            </tr>
          </thead>
          
             <tbody>
            @foreach ($movimientos as $pedido)
            <tr>
              <td>{{$pedido->identificador}}</td>
              <td>{{$pedido->fecha}}</td>
              <td><a target="_blank" href="{{url('/seguimientos/'.$pedido->clase.'/'.$pedido->identificador)}}">mas</a></td>
            </tr>
            @endforeach
          </tbody>
      </table>
      </div>

</div>
</div>
  <div class="col-lg-6">
    <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Clientes</h3>
      </div>
      <div class="box-body">
        <table  class="table PEDIDOS  table-bordered table-striped">
<thead>
            <tr>
              <th>Id</th>
              <th>fecha</th>
              <th>detalle</th>
            </tr>
          </thead>
          
             <tbody>
            @foreach ($cliente as $pedido)
            <tr>
              <td>{{cliente($pedido->identificador,1)}}</td>
              <td>{{$pedido->fecha}}</td>
              <td><a target="_blank" href="{{url('/seguimientos/'.$pedido->clase.'/'.$pedido->identificador)}}">mas</a></td>
            </tr>
            @endforeach
          </tbody>
      </table>
      </div>

</div>
</div>
 <div class="col-lg-6">
    <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">bodega</h3>
      </div>
      <div class="box-body">
        <table  class="table PEDIDOS  table-bordered table-striped">
<thead>
            <tr>
              <th>Id</th>
              <th>fecha</th>
              <th>detalle</th>
            </tr>
          </thead>
          
             <tbody>
            @foreach ($bodega as $pedido)
            <tr>
              <td>{{validarbodega($pedido->identificador)}}</td>
              <td>{{$pedido->fecha}}</td>
              <td><a target="_blank" href="{{url('/seguimientos/'.$pedido->clase.'/'.$pedido->identificador)}}">mas</a></td>
            </tr>
            @endforeach
          </tbody>
      </table>
      </div>

</div>
</div>

  <div class="col-lg-6">
    <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">categorias</h3>
      </div>
      <div class="box-body">
        <table  class="table PEDIDOS  table-bordered table-striped">
<thead>
            <tr>
              <th>Id</th>
              <th>fecha</th>
              <th>detalle</th>
            </tr>
          </thead>
          
             <tbody>
            @foreach ($categoria as $pedido)
            <tr>
              <td>{{validarlista($pedido->identificador,4)}}</td>
              <td>{{$pedido->fecha}}</td>
              <td><a target="_blank" href="{{url('/seguimientos/'.$pedido->clase.'/'.$pedido->identificador)}}">mas</a></td>
            </tr>
            @endforeach
          </tbody>
      </table>
      </div>

</div>
</div>



 <div class="col-lg-6">
    <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Usuarios</h3>
      </div>
      <div class="box-body">
        <table  class="table PEDIDOS  table-bordered table-striped">
<thead>
            <tr>
              <th>Id</th>
              <th>fecha</th>
              <th>detalle</th>
            </tr>
          </thead>
          
             <tbody>
            @foreach ($usuarios as $pedido)
            <tr>
              <td>{{($pedido->identificador)}}</td>
              <td>{{$pedido->fecha}}</td>
              <td><a target="_blank" href="{{url('/seguimientos/'.$pedido->clase.'/'.$pedido->identificador)}}">mas</a></td>
            </tr>
            @endforeach
          </tbody>
      </table>
      </div>

</div>
</div>

</div>


<script type="text/javascript">
	$(function () {
		$(".PEDIDOS").DataTable({
       "sScrollY": "250px",
			"language": {
				"lengthMenu": "_MENU_",
				"zeroRecords": "No hay seguimientos registrados",
				"info": "Pagina _PAGE_ de _PAGES_",
				"infoEmpty": "",
				"infoFiltered": "(Filtrado de _MAX_ seguimientos )",
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
