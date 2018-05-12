@extends('layouts.app')

@section('htmlheader_title')
{{ trans('adminlte_lang::message.home') }}
@endsection

@section('main-content')

<div class="row">
  <div class="col-lg-12">
    <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">DETALLES</h3>
      </div>
      <div class="box-body">
        <table  class="table PEDIDOS  table-bordered table-striped">
<thead>
            <tr>
              <th>Usuario</th>
              <th>Bodega</th>
              <th>caso</th>
              <th>Ubicacion</th>
              <th>Fecha</th>
              <th>Descripcion</th>
            </tr>
          </thead>

             <tbody>
            @foreach ($seguimientos as $pedido)
            <tr>
              <td>{{$pedido->nombre}}</td>
              @if($pedido->bodega!=0)
              <td>{{validarbodega($pedido->bodega)}}</td>
              @else
              <td>No aplica</td>
              @endif
              <td>{{validarlista($pedido->caso,7)}}</td>
              <td><a target="_blank" href="{{quitarespacio($pedido->ubicacion)}}">{{$pedido->ubicacion}}</a></td>
              <td>{{$pedido->fecha}}</td>
              <td>{{$pedido->descripcion}}</td>

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
			"language": {
				"lengthMenu": "Mostrar _MENU_ por pagina",
				"zeroRecords": "No hay seguimientos registrados",
				"info": "Pagina _PAGE_ de _PAGES_",
				"infoEmpty": "",
				"infoFiltered": "(Filtrado de _MAX_ seguimientos )",
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
