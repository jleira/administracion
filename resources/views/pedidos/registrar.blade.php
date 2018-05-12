@extends('layouts.app')

@section('htmlheader_title')
admin  
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
		<strong>Lo sentimos! </strong> {{Session::get('error_message')}}
	</div>
	@endif

	<div class="col-lg-5">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Crear Reservacion </h3>
			</div>
			<div class="box-body">
				<form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('registrarpedido') }}">
 					{{ csrf_field() }}
 					<input type="hidden" value="0,0" name="posicion" id="posicion">

					<div class="form-group">
						<label>Tiempo de reservacion</label>

						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="tiempo" value="{{old('tiempo')}}" onchange="cambiarbodega()" class="form-control pull-right" id="reservation">
						</div>
						@if ($errors->has('tiempo') )
						<p style="color:red;margin:0px">{{ $errors->first('tiempo') }}</p>
						@endif
					</div>
					@if (Auth::user()->id_perfil==1)
					<div class="form-group" id="bodega2">
						<label >Bodega</label>
						<select class="form-control select2" id="bodega" style="width: 100%;" onchange="cambiarbodega()" name="bodega_item">
							<option value="">Seleccione bodega</option>
							@foreach ($bodegas as $bodega)
							<option value="{{$bodega->id}}">{{$bodega->nombre}}</option>
							@endforeach
						</select>
						@if ($errors->has('bodega_item') )
						<p style="color:red;margin:0px">{{ $errors->first('bodega_item') }}</p>
						@endif
					</div>
					@else
					<input type="hidden" name="bodega_item" id="bodega" value="{{Auth::user()->bodega_id}}">
					@endif
					<div class="form-group">
						<label for="">Seleccione el producto - cantidad</label>
						<div id="input1" class="clonedInput" style="padding:2px">
							<input value="1" type="number" name="cantidad[]" id="cantidad1" onchange="capturar2(1)" style="border-radius: 10px 0px 0px 10px;padding: 2px;border-style: groove;vertical-align: top;text-align: center;width: 50px;" />
							<select class="form-control select2"  style="width: 80%;" onchange="capturar2(1)" name="producto[]" id="produc1" >
								@foreach ($productos as $producto)
								<option value="{{$producto->codigo}}">{{$producto->codigo}} - {{$producto->nombre}}</option>
								@endforeach
							</select>
							<p for="" style="margin:0px" id="disp1"></p>
							@if ($errors->has('producto[]') )
							<p style="color:red;margin:0px">{{ $errors->first('producto[]') }}</p>
							@endif

						</div>
						@if ($errors->has('cantidad') )
						<p style="color:red;margin:0px">{{ $errors->first('cantidad') }}</p>
						@endif
						<div style="padding-top:4px" >
							<a  id="btnAdd" class="btn btn-primary btn-xs" value="+">+</a>
							<a  id="btnless" class="btn btn-danger btn-xs" value="-" disabled>-</a>
						</div>
					</div>

					<div class="form-group">

						<a href="javascript: submitform()" name="tiene" >Buscar disponibiliad<a/>
							<div class="form-group">
								<label for="">Cliente</label>
								<select class="form-control select2" style="width: 100%;" name="cliente" onchange="capturar()"  id="cliente">
									<option value="0">seleccione cliente</option>
									@foreach ($clientes as $cliente)
									<option value="{{$cliente->id}}">{{$cliente->nombre }} {{$cliente->apellido}}</option>
									@endforeach
								</select>
								@if ($errors->has('cliente') )
								<p style="color:red;margin:0px">{{ $errors->first('cliente') }}</p>
								@endif
								<div class="" id="direc">

								</div>
							</div>
							<div class="form-group">
								<label for="">Direccion</label>
								<input class="form-control" type="text" name="direccion" value="{{old('direccion')}}"/>
								@if ($errors->has('direccion') )
								<p style="color:red;margin:0px">{{ $errors->first('direccion') }}</p>
								@endif
							</div>

							<div class="form-group">
								<label for=""></label>
								<select class="form-control" name="estado">
									<option value="2">CONFIRMADO</option>
									<option value="8">COTIZACION</option>
									<option value="1">PENDIENTE</option>

								</select>
								<p class="help-block">Pendiente para aquellas ordenes que no se pagan en su totalidad<br>
									Confirmado para aquellas ordenes que se pagan en su totalidad</p>
								</div>
								<div class="form-group">
									<label>Fecha de evento</label>
									<div class='input-group date' id='datetimepicker1'>
										<input type='text' class="form-control" name="fecha_evento" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>Incluir IVA (19%)<input type='checkbox' class="checkbox" value="1" name="iva" /></label>

								</div>
							</div>
							@if(Auth::user()->id_perfil==1)
						<div class="form-group">
								<label>Abono</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-usd"></i>
									</div>
									<input type="text" onkeyup="format(this)" onchange="format(this)" class="form-control" name="abono" value="0">
								</div>
								@if ($errors->has('abono') )
								<p style="color:red;margin:0px">{{ $errors->first('abono') }}</p>
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
									<a  id="adddescuento" class="btn btn-primary btn-xs" value="+">+</a>
									<a  id="lessdescuento" class="btn btn-danger btn-xs" value="-" disabled>-</a>
								</div>
							</div>


							<div class="form-group">
								<label class="checkbox-inline"><input type="checkbox" onclick="showimpuesto()" id="impuesto" value="1" name="impuestos">Habilitar Recargo</label>
							</div>
							<div class="form-group" style="display:none" id="impu">
								<label>Recargo</label>
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
									<a  id="addimpuesto" class="btn btn-primary btn-xs" value="+">+</a>
									<a  id="lessimpuesto" class="btn btn-danger btn-xs" value="-" disabled>-</a>
								</div>
							</div>
		
							<div class="form-group">
								<button type="submit" formtarget="_blank" class="btn btn-danger" name="cotizar" value="1">Cotizar</button>
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
		<div class="col-lg-7" id="disponibles"></div>
		<div class="col-lg-7" id="disponible2"></div>

	</div>

	<script src="{{ asset('public/plugins/select2/select2.full.min.js')}}"></script>
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
		function cambiarbodega(){
			document.getElementById("disponibles").innerHTML="";
			document.getElementById("disponible2").innerHTML="";
		}
		function detalleproducto(producto){
			document.getElementById("disponible2").innerHTML="";
			fecha=document.getElementById('reservation').value;
			fecha=fecha.replace(" - ", "-");
			fecha=fecha.replace(" ", "");
			fecha=fecha.replace(" ", "");
			fecha=fecha.replace(" ", "");
			fecha=fecha.replace(" ", "");
			bodega=document.getElementById('bodega').value;	
			if (fecha=="") {
				alert("debe seleccionar un rango de fecha");
			}else if (bodega==0) {
				alert("debe seleccionar una bodega");
			}else {
				var url='buscarproductoenreservas?fecha='+fecha+"&bodega="+bodega+"&producto="+producto;
				$('#disponible2').load("{!!url('"+url+"')!!}");
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

	$('#produc1').select2();
	function capturar() {
		direccion = document.getElementById("direc");
		var cliente = document.getElementById('cliente');
		if(cliente.options[cliente.selectedIndex].value!=0){
			direccion.style.display='block';
			var url='verdireccion?id='+cliente.options[cliente.selectedIndex].value;
			$("#direc").load("{!!url('"+url+"')!!}");
		}else{
			direccion.style.display='none';
		}
	}
	function capturar2(id) {
		document.getElementById("disponibles").innerHTML="";
		document.getElementById("disponible2").innerHTML="";
		cantidad=document.getElementById('cantidad'+id).value;
		product=document.getElementById('produc'+id).value;
		fecha=document.getElementById('reservation').value;
		fecha=fecha.replace(" - ", "-");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		bodega=document.getElementById('bodega').value;
		if (fecha=="") {
			alert("debe seleccionar un rango de fecha");
		}else if (bodega==0) {
			alert("debe seleccionar una bodega");
		}else {
			var url='confirmar1?productid='+product +"&cantidad="+cantidad+"&tiempo="+fecha+"&bodega="+bodega;
			$('#disp'+id).load("{!!url('"+url+"')!!}");

		}
	}

	function submitform()
	{
		document.getElementById("disponibles").innerHTML="";
		document.getElementById("disponible2").innerHTML="";
		var num     = $('.clonedInput').length;
		var product="";
		var cantidad="";
		for (i = 1; i < num+1; i++) {
			product = product + document.getElementById('produc'+i).value+"-";
			cantidad = cantidad + document.getElementById('cantidad'+i).value+"-";
		}
		fecha=document.getElementById('reservation').value;
		fecha=fecha.replace(" - ", "-");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		bodega=document.getElementById('bodega').value;
		var url='confirmar?productid='+product +"&cantidad="+cantidad+"&tiempo="+fecha+"&bodega="+bodega+"&caso=0";

		if (bodega==0) {
			alert('seleccione una bodega');
		}else {
			$("#disponibles").load("{!!url('"+url+"')!!}");
		}
	}
	$(document).ready(function() {
		$('#datetimepicker1').datetimepicker();

		$('#reservation').daterangepicker({
			format: 'DD/MM/YYYY hh:mm A',
			timePickerIncrement: 30,
			timePicker: true,
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
		$('#produc').select2({
			language: {

				noResults: function() {

					return "No hay resultado";
				},
				searching: function() {

					return "Buscando..";
				}
			}
		});
		$('#cliente').select2({
			language: {

				noResults: function() {

					return "No hay resultado";
				},
				searching: function() {

					return "Buscando..";
				}
			}
		});
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


		$('#btnAdd').click(function() {
			var num     = $('.clonedInput').length;
			var newNum  = new Number(num + 1);
			var newElem = $('#input' + num).clone().attr('id', 'input' + newNum);
			newElem.children(':first').attr('id', 'name' + newNum).attr('class', 'width:60%').attr('id', 'produc' + newNum).attr('onchange','capturar2('+newNum+')');
			newElem.find("input").attr('id', 'cantidad' + newNum);
			newElem.find("span").remove();
			newElem.find("p").attr('id', 'disp' + newNum);
			newElem.find("select").select2().attr('onchange','capturar2('+newNum+')').attr('id', 'produc' + newNum);
			$('#input' + num).after(newElem);
			$("#btnless").removeAttr("disabled");
		});
		$('#btnless').click(function() {
			var num = $('.clonedInput').length;
			if (num==2) {
				$('#btnless').attr('disabled','disabled');
				newElem = $('#input' + num).remove();
			}else {
				newElem = $('#input' + num).remove();
			}
		});

	});
</script>
@endsection
