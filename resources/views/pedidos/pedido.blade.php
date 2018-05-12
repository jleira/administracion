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
		@if(Session::has('error_message'))
		<div class="alert alert-danger alert-dismissable fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>ERROR! </strong> {{Session::get('error_message')}}
		</div>
		@endif


		@foreach ($reserva as $reserva)
		<div class="col-lg-4">
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title">Orden {{$reserva->id}}</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Item</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>ID</td>
									<td>{{$reserva->id}}</td>
								</tr>
								<tr>
									<td>CLIENTE</td>
									<td>{{cliente($reserva->cliente,1)}}</td>
								</tr>
								<tr>
									<td>FECHA DE EVENTO</td>
									<td>{{formatofecha($reserva->fecha_evento)}}</td>
								</tr>
								<tr>
									<td>FECHA DE DESPACHO</td>
									<td>{{formatofecha($reserva->desde)}}</td>
								</tr>
								<tr>
									<td>FECHA DE RECOGIDA</td>
									<td>{{formatofecha($reserva->hasta)}}</td>
								</tr>
								<tr>
									<td>ESTADO</td>
									<td>{{validarlista($reserva->estado,6)}}</td>
								</tr>
								<tr>
									<td>Bodega</td>
									<td>{{validarbodega($reserva->bodega)}}</td>
								</tr>

								@foreach ($valores as $valor)
								<tr>
									{{buscar_descuentos($valor->concepto_descuentos,$valor->descuentos)}}
									{{buscar_recargos($valor->concepto_impuestos,$valor->impuestos)}}
									@php
									$total=$valor->total_facturado+array_sum(explode(',',$valor->impuestos))-array_sum(explode(',',$valor->descuentos))

									@endphp
									<td>TOTAL (sin iva)</td>
									<td>$ {{number_format($total)}}</td>
								</tr>

								@if($valor->iva==1)
								<tr>
									<td>Iva</td>
									<td>$ {{number_format($total*0.19)}}</td>
								</tr>
								<tr>
									<td>TOTAL( iva incluido)</td>
									<td>$ {{number_format($total*1.19)}}</td>
								</tr>

								@endif

								<tr>
									<td>ABONADO</td>
									<td>$ {{number_format($valor->total_abonado)}}</td>
								</tr>
								@if($valor->iva==1)
								<tr>
									<td>SALDO</td> 
									<td>$ {{number_format(($total*1.19)-$valor->total_abonado)}}</td>
								</tr>
								@else
								<tr>
									<td>SALDO</td> 
									<td>$ {{number_format($total-$valor->total_abonado)}}</td>
								</tr>
								@endif

								@endforeach


							</tbody>
						</table>

						<form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('actualizarorden') }}">
							{{ csrf_field() }}
							<input type="hidden" name="id" value="{{$reserva->id}}">
							<input type="hidden" value="0,0" name="posicion" id="posicion">

							<div class="form-group">
								@if($valor->iva==1)
								<label class="checkbox-inline"><input type="checkbox" name="iva" value="1" checked>IVA (19%)</label>

								@else
								<label class="checkbox-inline"><input type="checkbox" name="iva" value="1">IVA (19%)</label>

								@endif
							</div>
							@if(Auth::user()->id_perfil==1)

							<div class="form-group">
								<label for="">ABONO</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-usd"></i>
									</div>
									<input type="text" onkeyup="format(this)" onchange="format(this)"  name="Abono" value="{{old('Abono')}}" class="form-control" id="reservation">
								</div>
								@if ($errors->has('Abono') )
								<p style="color:red;margin:0px">{{ $errors->first('Abono') }}</p>
								@endif
							</div>
							@endif
							<div class="form-group">
								<label class="checkbox-inline"><input type="checkbox" onclick="descuento()" id="descuentos" value="1" name="descuentos">Habilitar Descuentos</label>
							</div>


							<div class="form-group" style="display:none" id="desc">
								<label>Descuento</label>
								<div id="inputdesc1" class="inputdescuento" style="padding:2px">
									<input type="text" class="form-control" name="Concepto[]" placeholder="concepto">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-usd"></i>
										</div>
										<input type="text" onkeyup="format(this)" onchange="format(this)" class="form-control" name="descuento[]" value="0">
									</div>
								</div>
								@if ($errors->has('Concepto') )
								<p style="color:red;margin:0px">{{ $errors->first('Concepto') }}</p>
								@endif


								<div style="padding-top:4px" >
									<a id="adddescuento" class="btn btn-primary btn-xs" value="+">+</a>
									<a  id="lessdescuento" class="btn btn-danger btn-xs" value="-" disabled>-</a>
								</div>
							</div>


							<div class="form-group">
								<label class="checkbox-inline"><input type="checkbox" onclick="showimpuesto()" id="impuesto" value="1" name="impuestos">Habilitar Impuestos</label>
							</div>
							<div class="form-group" style="display:none" id="impu">
								<label>Impuestos</label>
								<div id="inputimpuest1" class="inputimpuesto" style="padding:2px">
									<input type="text" class="form-control" name="conceptoimpuesto[]" placeholder="concepto">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-usd"></i>
										</div>
										<input type="text" onkeyup="format(this)" onchange="format(this)" class="form-control" name="impuesto[]" value="0">
									</div>
								</div>

								<div style="padding-top:4px" >
									<a id="addimpuesto" class="btn btn-primary btn-xs" value="+">+</a>
									<a  id="lessimpuesto" class="btn btn-danger btn-xs" value="-" disabled>-</a>
								</div>
							</div>



							<div class="form-group">

								<div id="registrar">
									@if (validarcertificado())

									@else
									<button type="submit" class="btn btn-primary">Registrar</button>
									@endif
									</div>
								
							</div>

						</form>

					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title">Productos </h3>
				</div>
				<div class="box-body">
					<table class="table table-responsive table-hover">
						{{productos($reserva->producto,$reserva->cantidad,$reserva->cliente)}}
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('generarguia') }}">
				{{ csrf_field() }}
				<input type="hidden" name="reserva" value="{{$reserva->id}}">
				<div class="form-group">
				<button type="submit" formtarget="_blank" class="btn btn-info" name="generarfactura" value="4">Vista previa(Impresion)</button>	
					<button type="submit" formtarget="_blank" class="btn btn-success" name="generarfactura" value="5">Imprimir(pdf)</button>
					<button type="submit" formtarget="_blank" class="btn btn-danger" name="generarfactura" value="1">Generar Factura(pdf)</button>
					<button type="submit" formtarget="_blank" class="btn btn-success" name="generarfactura" value="2">Generar Factura(excel)</button>
					<button type="submit" formtarget="_blank" class="btn btn-warning" name="generarfactura" value="3">Constancia de pagos</button>
					@if($reserva->estado==6 or $reserva->estado==4 or $reserva->estado==5)

					@else
					<a href="{{url('editarreserva/'.$reserva->id)}}" class="btn btn-info">editar</a>

					@endif
					

				</div>
			</form>
			<form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('cambiarestado') }}">
				{{ csrf_field() }}
				<input type="hidden" name="reserva" value="{{$reserva->id}}">
				<input type="hidden" name="estado" value="{{$reserva->estado}}">
				@if($reserva->estado==1)
				<button type="submit" class="btn btn-success" name="confirmarpedido" value="2">Confirmar pedido</button>
				<button type="submit" class="btn btn-danger" name="cancelarpedido" value="2">Cancelar Pedido</button>
				@elseif($reserva->estado==2)
				<button type="submit" class="btn btn-danger" name="cancelarpedido" value="2">Cancelar Pedido</button>
				<button type="submit" class="btn btn-success" name="confirmar_despacho" value="2">Confirmar Despacho</button>
				@elseif($reserva->estado==3)
				<button type="submit" class="btn btn-danger" name="cancelarpedido" value="2">Cancelar pedido</button>
				<button type="submit" class="btn btn-success" name="entregado" value="2">Confirmar entrega</button>
				@elseif($reserva->estado==4)
				<button type="submit" class="btn btn-info" name="recoger" value="2">Recoger</button>
				<button type="submit" class="btn btn-success" name="recogidaexitosa" value="2">Recogida exitosa</button>
				@elseif($reserva->estado==5)
				<button type="submit" class="btn btn-success" name="recogidaexitosa" value="2">Confirmar recogida</button>
				@elseif($reserva->estado==8)
				<button type="submit" class="btn btn-danger" name="cancelarpedido" value="2">Cancelar Cotizacion</button>
				<button type="submit" class="btn btn-success" name="aceptarcotizacion" value="2">Aceptar Cotizacion</button>
				@endif
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
					document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>'; 
				}
			}
		}

		function MostrarPosition(position)
		{
			document.getElementById('posicion').value=position.coords.latitude+",	" + position.coords.longitude; 
			document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>'; 

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

				document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>'; 

				break;
				case error.UNKNOWN_ERROR:

				document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>'; 

				break;
			}
		}
		function descuento() {

			if (document.getElementById('descuentos').checked) {
				document.getElementById('desc').style.display="block";
			}else {
				document.getElementById('desc').style.display="none";
			}
		}
		function showimpuesto() {
			if (document.getElementById('impuesto').checked) {
				document.getElementById('impu').style.display="block";
			}else {
				document.getElementById('impu').style.display="none";
			}
		}
		$('#adddescuento').click(function() {
			var num     = $('.inputdescuento').length;
			var newNum  = new Number(num + 1);
			var newElem = $('#inputdesc' + num).clone().attr('id', 'inputdesc' + newNum);
			newElem.children(':first').attr('id', 'name' + newNum).attr('class', 'form-control').attr('id', 'produc' + newNum);
			newElem.find("input").attr('id', 'cantidad' + newNum);
			$('#inputdesc' + num).after(newElem);
			$("#lessdescuento").removeAttr("disabled");

		});

		$('#addimpuesto').click(function() {
			var num     = $('.inputimpuesto').length;
			var newNum  = new Number(num + 1);
			var newElem = $('#inputimpuest' + num).clone().attr('id', 'inputimpuest' + newNum);
			newElem.children(':first').attr('id', 'name' + newNum).attr('class', 'form-control').attr('id', 'produc' + newNum);
			newElem.find("input").attr('id', 'cantidad' + newNum);
			$('#inputimpuest' + num).after(newElem);
			$("#lessimpuesto").removeAttr("disabled");

		});

		$('#lessimpuesto').click(function() {
			var num     = $('.inputimpuesto').length;
			if (num==2) {
				$('#lessimpuesto').attr('disabled','disabled');
				newElem = $('#inputimpuest' + num).remove();
			}else {
				newElem = $('#inputimpuest' + num).remove();
			}
		});


		$('#lessdescuento').click(function() {
			var num     = $('.inputdescuento').length;
			if (num==2) {
				$('#lessdescuento').attr('disabled','disabled');
				newElem = $('#inputdesc' + num).remove();
			}else {
				newElem = $('#inputdesc' + num).remove();
			}
		});

		function format(input)
		{

			var num = input.value.replace(/\./g,'');
			if(!isNaN(num)){
				num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
				num = num.split('').reverse().join('').replace(/^[\.]/,'');
				input.value = num;
			}

			else{ alert('Solo se permiten numeros');
			input.value = input.value.replace(/[^\d\.]*/g,'');
		}
	}
</script>

@endsection
