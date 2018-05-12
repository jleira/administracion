@extends('layouts.app')
@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')

<div class="modal fade" id="movimiento" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Detalles del movimiento</h4>
        </div>
        <div class="modal-body" id="mov">

	      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>



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
			<strong>Eroor! </strong> {{Session::get('error_message')}}
		</div>
		@endif

						<div class="col-xs-12">
							<div class="box box-primary">
							            <div class="box-header">
							              <h3 class="box-title">movimientos</h3>
							            </div>
							            <!-- /.box-header -->
							            <div class="box-body">
							              <table id="movimientos" class="table table-bordered table-striped">
							                <thead>
							                <tr>
																<th>id</th>
							                  <th>movimiento</th>
																<th>productos</th>
							                  <th>bodega</th>
							                  <th>fecha</th>
							                </tr>
							                </thead>
							                <tbody>
															@foreach ($movimientos as $movimiento)
																<tr onclick="modal({{$movimiento->id_m}})">
																	<td>{{$movimiento->id_m}}</td>
																	<td>{{validarproductoporcodigo($movimiento->producto_id)}}</td>
																	<td>{{validarlista($movimiento->item,1)}}</td>
																	<td>{{validarbodega($movimiento->bodega)}}</td>
                                  <td>{{$movimiento->creado}}</td>
																</tr>
															@endforeach

															</tbody>
														  <tfoot>
														  <tr>
																<th>id</th>
																<th>movimiento</th>
																<th>productos</th>
																<th>bodega</th>
																<th>fecha</th>
															 </tr>
															 </tfoot>
															 </table>
														 </div>
														 </div>
													 </div>


</div>

<script src="public/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">
function modal(id) {
	$("#mov").load("{!!url('/movimiento?id="+id+"')!!}");
 $("#movimiento").modal();
}
 $("#produc").select2();
  $("#item").select2();
	function capturar() {
	  verbodegas = document.getElementById("bodega");
	  var item = document.getElementById('item');
	  if(item.options[item.selectedIndex].value==3)
	verbodegas.style.display='block';
	  else
	verbodegas.style.display='none';
	}
$(function () {
	$("#movimientos").DataTable({
		"language": {
					"lengthMenu": "Mostrar _MENU_ movimientos por pagina",
					"zeroRecords": "No hay movimientos registrados",
					"info": "Pagina _PAGE_ de _PAGES_",
					"infoEmpty": "",
					"infoFiltered": "(Filtrado de _MAX_ movimientos )",
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
