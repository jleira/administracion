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

<div class="col-md-12">
	<div class="box box-primary">
	            <div class="box-header">
	              <h3 class="box-title">Categorias</h3>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
	              <table id="categorias" class="table table-bordered table-striped">
	                <thead>
	                <tr>
	                  <th>Id</th>
	                  <th>Nombre</th>
	                  <th>Descripcion</th>
	                  <th>codigo</th>
	                  <th>mas</th>
	                </tr>
	                </thead>
	                <tbody>
									@foreach ($categorias as $categoria)
										<tr>
											<td>{{$categoria->id_lista}}</td>
											<td>{{$categoria->valor_item}}</td>
											<td>{{$categoria->descripcion}}</td>
											<td>{{$categoria->valor_lista}}</td>
											<td><a href="{{ url('editarcategoria/'.$categoria->valor_lista)}}">editar</a></td>
										</tr>
									@endforeach

									</tbody>
								  <tfoot>
										<th>Id</th>
	                  <th>Nombre</th>
	                  <th>Descripcion</th>
	                  <th>codigo</th>
	                  <th>mas</th>
									 </tr>
									 </tfoot>
									 </table>
								 </div>
								 </div>
							 </div>


</div>
<script type="text/javascript">
$(function () {
	$("#categorias").DataTable({
		"language": {
					"lengthMenu": "Mostrar _MENU_ productos por pagina",
					"zeroRecords": "No hay productos registrados",
					"info": "Pagina _PAGE_ de _PAGES_",
					"infoEmpty": "Ninguna categoria encontrada",
					"infoFiltered": "(Filtrado de _MAX_ categorias )",
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
