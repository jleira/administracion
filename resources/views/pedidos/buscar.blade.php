@extends('layouts.app')

@section('htmlheader_title')
{{ trans('adminlte_lang::message.home') }}
@endsection

@section('main-content')


<div class="row">
	<div class="col-lg-6">
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-calendar"></i>
			</div>
			<input type="text" name="tiempo" class="form-control pull-right" id="reservation" >
		</div>
		@if(Auth::user()->id_perfil==1)
				<div class="form-group">
				<label>Bodega</label>
			<select id="bodega" class="form-control">
				<option value="0" selected="">Todas las bodegas</option>
				@foreach($bodegas as $bodega)
				<option value="{{$bodega->id}}">{{$bodega->nombre}}</option>
				@endforeach
			</select>
	</div>
	@else
<input type="hidden" id="bodega" value="{{Auth::user()->bodega_id}}">

	@endif
			<div class="form-group">
			<a href="javascript:cargarpedidos()" class="btn btn-success"> Buscar</a>
	</div>
	</div>
	<div id="reservas" class="col-lg-12">

	<div class="col-lg-12">

		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Pedidos</h3>
			</div>
			<div class="box-body">
				<table id="pedidos" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>id</th>
							<th>fecha de montaje</th>
							<th>fecha de desmontaje</th>
							<th>cliente</th>
							<th>recepcion</th>
							<th>bodega</th>
							<th>estado</th>
							<th>MAS</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($reservas as $reserva)
						@if($reserva->estado!=8)
						<tr>
							<td>{{$reserva->id}}</td>
							<td>{{formatofecha($reserva->desde)}}</td>
							<td>{{formatofecha($reserva->hasta)}}</td>
							<td>{{cliente($reserva->cliente,1)}}</td>
							<td>{{$reserva->recepcion}}</td>
							<td>{{validarbodega($reserva->bodega)}}</td>
							<td>{{validarlista($reserva->estado,6)}}</td>
							<td><a href="{{url('orden/'.$reserva->id)}}">mas</a></td>
						</tr>
						@endif
						@endforeach

					</tbody>
					<tfoot>
						<tr>
							<th>Codigo</th>
							<th>Categoria</th>
							<th>Nombre</th>
							<th>Valor Mayorista</th>
							<th>Valor Minorista</th>
							<th>Detalles</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>



	<div class="col-lg-12">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Cotizaciones</h3>
			</div>
			<div class="box-body">
				<table id="pedidos2" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>id</th>
							<th>fecha de montaje</th>
							<th>fecha de desmontaje</th>
							<th>cliente</th>
							<th>recepcion</th>
							<th>bodega</th>
							<th>MAS</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($reservas as $reserva)
						@if($reserva->estado==8)
						<tr>
							<td>{{$reserva->id}}</td>
							<td>{{formatofecha($reserva->desde)}}</td>
							<td>{{formatofecha($reserva->hasta)}}</td>
							<td>{{cliente($reserva->cliente,1)}}</td>
							<td>{{$reserva->recepcion}}</td>
							<td>{{validarbodega($reserva->bodega)}}</td>
							<td><a href="{{url('orden/'.$reserva->id)}}">mas</a></td>
						</tr>
						@endif
						@endforeach

					</tbody>
					<tfoot>
						<tr>
							<th>Codigo</th>
							<th>Categoria</th>
							<th>Nombre</th>
							<th>Valor Mayorista</th>
							<th>Valor Minorista</th>
							<th>Detalles</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

</div>
<script type="text/javascript">
	function cargarpedidos(){
		fecha=document.getElementById('reservation').value;
		fecha=fecha.replace(" - ", "-");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		bodega=document.getElementById('bodega').value;		
		if (fecha=="") {
			alert("debe seleccionar un rango de fecha");
		}else{
			var url='rangoreservas?tiempo='+fecha+"&bodega="+bodega;
			$('#reservas').load("{!!url('"+url+"')!!}");
		}
	}

	$(function () {

		$("#pedidos").DataTable({
			"language": {
				"lengthMenu": "Mostrar _MENU_ pedidos por pagina",
				"zeroRecords": "No hay pedidos registrados",
				"info": "Pagina _PAGE_ de _PAGES_",
				"infoEmpty": "",
				"infoFiltered": "(Filtrado de _MAX_ pedido )",
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
		$("#pedidos2").DataTable({
			"language": {
				"lengthMenu": "Mostrar _MENU_ cotizaciones por pagina",
				"zeroRecords": "No hay cotizaciones registrados",
				"info": "Pagina _PAGE_ de _PAGES_",
				"infoEmpty": "",
				"infoFiltered": "(Filtrado de _MAX_ cotizaciones )",
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
		$('#reservation').daterangepicker({
			format: 'DD/MM/YYYY hh:mm A',
			timePickerIncrement: 30,
			timePicker: true,
			startDate: '2/7/2017 13:10',
			endDate: '3/7/2017 10:12',
			locale: {
				"separator": " - ",
				"applyLabel": "Aceptar",
				"cancelLabel": "Cancelar",
				"fromLabel": "Desde",
				"toLabel": "Hasta",
				"customRangeLabel": "Custom",
				"daysOfWeek": [
				"Do",
				"Lu",
				"Ma",
				"Mi",
				"Ju",
				"Vi",
				"Sa"
				],
				"monthNames": [
				"Enero",
				"Febrero",
				"Marzo",
				"Abril",
				"Mayo",
				"Junio",
				"Julio",
				"Agusto",
				"Septiembre",
				"Octubre",
				"Noviembre",
				"Diciembre"        ],

			}


		});
	});

</script>


@endsection
