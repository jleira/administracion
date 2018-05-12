@extends('layouts.app')

@section('htmlheader_title')
{{ trans('adminlte_lang::message.home') }}
@endsection
<style type="text/css">
	.disableda {
		pointer-events: none;
		opacity: 0.6;
		color: gray;
	}
	.disabledb {
		pointer-events: visible;
		opacity: 1;
		color: green;
	}
	.eliminarp {
		color: red;
	}
</style>
@section('main-content')

<div class="row">
<form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('editarorden') }}">
			{{ csrf_field() }}

	@foreach ($reserva as $reserva)
	<div class="col-lg-12">		
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title">Orden con Id: {{$reserva->id}}</h3>
					<input type="hidden" name="id" id="orden" value="{{$reserva->id}}">
					<input type="hidden" value="0,0" name="posicion" id="posicion">
				</div>
				<!-- /.box-header -->

				<div class="box-body">
					<div class="col-lg-6">
						<div class="form-group">
							<input type="hidden" name="reserva_desde" id="desde" value="{{$reserva->desde}}">
							<input type="hidden" name="reserva_evento" id="evento" value="{{$reserva->fecha_evento}}">
							<input type="hidden" name="fechajs" id="fechajs" value="{{fechajs($reserva->fecha_evento)}}">
							<input type="hidden" name="reserva_hasta" id="hasta" value="{{$reserva->hasta}}">
							<input type="hidden" value="{{fechajs2($reserva->desde)}} - {{fechajs2($reserva->hasta)}}" id="reservation2">
						
								<label style="width: 100%;">Tiempo de reservacion<label class="pull-right"><input  type="checkbox" name="editarfecha" onchange="habilitarfechas()" id="editarfechas" value="1">Editar Fechas</label></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" name="tiempo" onchange="cambiarfecha()" value="{{fechajs2($reserva->desde)}} - {{fechajs2($reserva->hasta)}}" class="form-control pull-right" id="reservation" disabled>
								</div>
								@if ($errors->has('tiempo') )
								<p style="color:red;margin:0px">{{ $errors->first('tiempo') }}</p>
								@endif
								<div class="form-group">
									<label>Fecha de evento</label>
									<div class='input-group date' id='datetimepicker1'>
										<input type='text' class="form-control" id="fecha_evento" name="fecha_evento" disabled/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								@if ($errors->has('fecha_evento') )
								<p style="color:red;margin:0px">{{ $errors->first('fecha_evento') }}</p>
								@endif

						</div>
						<div class="form-group">
							<label for="">Cliente</label>
							<select class="form-control select2" style="width: 100%;" name="cliente" onchange="capturar()"  id="cliente">
								<option value="{{$reserva->cliente}}">{{cliente($reserva->cliente,1)}}</option>
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
							<input class="form-control" type="text" name="direccion" onchange="act()" value="{{$reserva->recepcion}}"/>
							@if ($errors->has('direccion') )
							<p style="color:red;margin:0px">{{ $errors->first('direccion') }}</p>
							@endif
						</div>
						@if (Auth::user()->id_perfil==1)
						<div class="form-group" id="bodega2">
							<label >Bodega</label>
							<select class="form-control select2" id="bodega" style="width:100%;" onchange="cambiarfecha()" name="bodega_item">
								<option value="{{$reserva->bodega}}">{{validarbodega($reserva->bodega)}}</option>
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
							<input value="0" type="number" name="cantidad[]" id="cantidad1" onchange="capturar3(1)" style="border-radius: 10px 0px 0px 10px;padding: 2px;border-style: groove;vertical-align: top;text-align: center;width: 50px;" />
							<select class="form-control select2"  style="width: 80%;" onchange="capturar3(1)" name="producto[]" id="produc1" >
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
			<div class="col-lg-12" id="disponible">

			</div>
			<div class="form-group" id="botonact">
									@if (validarcertificado())

						@else
				<button type="submit" id="boton" class="disabledb btn btn-warning">Actualizar</button>
						@endif

			</div>
					</div>
					<div class="col-lg-6">
						<div id="productoseditar">
							{{productosedicion($reserva->id,$reserva->producto,$reserva->cantidad)}}
						</div>

					</div>
					<div id="disp">
						<input type="hidden" name="" id="decide" value="0">
					</div>
				</div>
			</div>
			</div>

			<div class="col-lg-6" id="disponible2">

			</div>
	@endforeach
	</form>

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
							document.getElementById('botonact').innerHTML='<button type="submit" id="boton" class="disabledb btn btn-warning">Actualizar</button>'; 
			}
		}
	}

	function MostrarPosition(position)
	{
		document.getElementById('posicion').value=position.coords.latitude+"," + position.coords.longitude; 
		document.getElementById('botonact').innerHTML='<button type="submit" id="boton" class="disabledb btn btn-warning">Actualizar</button>'; 

	}
	function mostrarerrores(error) {
		switch(error.code) {
			case error.PERMISSION_DENIED:
			alert("Para poder enviar el formulario debe proporcionar su ubicacion");
			break;
			case error.POSITION_UNAVAILABLE:
			  
			document.getElementById('botonact').innerHTML='<button type="submit" id="boton" class="disabledb btn btn-warning">Actualizar</button>'; 
			break;
			case error.TIMEOUT:
			  
			document.getElementById('botonact').innerHTML='<button type="submit" id="boton" class="disabledb btn btn-warning">Actualizar</button>'; 

			break;
			case error.UNKNOWN_ERROR:
			  
			document.getElementById('botonact').innerHTML='<button type="submit" id="boton" class="disabledb btn btn-warning">Actualizar</button>'; 

			break;
		}
	}


	function act(){jd
		$('#boton').addClass('disabledb');
	}
	$('#produc1').select2();
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
	function cambiarfecha(){
		if (window.location.protocol != "https:") {
		document.getElementById("disponible").innerHTML="";
		document.getElementById("disponible2").innerHTML="";
		document.getElementById("botonact").innerHTML="";
		fecha=document.getElementById('reservation').value;
		fecha=fecha.replace(" - ", "-");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		bodega=document.getElementById('bodega').value;	
		orden=document.getElementById('orden').value;
if (fecha=="") {
			alert("debe seleccionar un rango de fecha");
		}else if (bodega==0) {
			alert("debe seleccionar una bodega");
		}else {
			var url='confirmarcambiofecha?fecha='+fecha+"&bodega="+bodega+"&orden="+orden+"&caso=1";
			$('#disponible').load("{!!url('"+url+"')!!}");
		}	

		}else{

			if (navigator.geolocation)
			{    
				navigator.geolocation.getCurrentPosition(Mfecha,cfecha);
			}
			else
			{
				alert("Geolocalizacion no soportada por el navegador");
document.getElementById("disp").innerHTML="";
		document.getElementById("disponible").innerHTML="";
		document.getElementById("disponible2").innerHTML="";
		document.getElementById("botonact").innerHTML="";
		fecha=document.getElementById('reservation').value;
		fecha=fecha.replace(" - ", "-");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		bodega=document.getElementById('bodega').value;	
		orden=document.getElementById('orden').value;
if (fecha=="") {
			alert("debe seleccionar un rango de fecha");
		}else if (bodega==0) {
			alert("debe seleccionar una bodega");
		}else {
			var url='confirmarcambiofecha?fecha='+fecha+"&bodega="+bodega+"&orden="+orden+"&caso=1";
			$('#disponible').load("{!!url('"+url+"')!!}");
		}	
				
			}
		}


	}
	function Mfecha(position){
		document.getElementById('posicion').value=position.coords.latitude+"," + position.coords.longitude; 
		 
		document.getElementById("disp").innerHTML="";
		document.getElementById("disponible").innerHTML="";
		document.getElementById("disponible2").innerHTML="";
		document.getElementById("botonact").innerHTML="";
		fecha=document.getElementById('reservation').value;
		fecha=fecha.replace(" - ", "-");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		bodega=document.getElementById('bodega').value;	
		orden=document.getElementById('orden').value;
if (fecha=="") {
			alert("debe seleccionar un rango de fecha");
		}else if (bodega==0) {
			alert("debe seleccionar una bodega");
		}else {
			var url='confirmarcambiofecha?fecha='+fecha+"&bodega="+bodega+"&orden="+orden+"&caso=1";
			$('#disponible').load("{!!url('"+url+"')!!}");
		}	

	}
	function cfecha(error){
		document.getElementById("disp").innerHTML="";
		document.getElementById("disponible").innerHTML="";
		document.getElementById("disponible2").innerHTML="";
		document.getElementById("botonact").innerHTML="";
		fecha=document.getElementById('reservation').value;
		fecha=fecha.replace(" - ", "-");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		bodega=document.getElementById('bodega').value;	
		orden=document.getElementById('orden').value;
		switch(error.code) {
			case error.PERMISSION_DENIED:
			alert("Para poder enviar el formulario debe proporcionar su ubicacion");
			break;
			case error.POSITION_UNAVAILABLE:
			  
		if (fecha=="") {
			alert("debe seleccionar un rango de fecha");
		}else if (bodega==0) {
			alert("debe seleccionar una bodega");
		}else {
			var url='confirmarcambiofecha?fecha='+fecha+"&bodega="+bodega+"&orden="+orden+"&caso=1";
			$('#disponible').load("{!!url('"+url+"')!!}");
		}	
		break;
			case error.TIMEOUT:
			  
		if (fecha=="") {
			alert("debe seleccionar un rango de fecha");
		}else if (bodega==0) {
			alert("debe seleccionar una bodega");
		}else {
			var url='confirmarcambiofecha?fecha='+fecha+"&bodega="+bodega+"&orden="+orden+"&caso=1";
			$('#disponible').load("{!!url('"+url+"')!!}");
		}
			break;
			case error.UNKNOWN_ERROR:
			  
		if (fecha=="") {
			alert("debe seleccionar un rango de fecha");
		}else if (bodega==0) {
			alert("debe seleccionar una bodega");
		}else {
			var url='confirmarcambiofecha?fecha='+fecha+"&bodega="+bodega+"&orden="+orden+"&caso=1";
			$('#disponible').load("{!!url('"+url+"')!!}");
		}
			break;
		}
	
		
	}

	function editarproducto(id, producto)
	{

		if (confirm("esta seguro que desea editar este producto")) {
			orden=document.getElementById('orden').value;
			cantidad=document.getElementById('cantidadfinal'+id).value;
			ubicacion=document.getElementById("posicion").value;
			ubicacion=ubicacion.replace(",","a");
			$("#productoseditar").load("{{url('editarpedido/editarproducto')}}"+"/"+orden+"/"+producto+"/"+cantidad+"/"+ubicacion);
		}
	}

	function eliminarproducto(id) {
		if (confirm("esta seguro que desea eliminar este producto")) {
			ubicacion=document.getElementById("posicion").value;
			ubicacion=ubicacion.replace(",","a");

			$("#productoseditar").load("{{url('editarpedido/eliminarproducto')}}"+"/"+id+"/"+ubicacion);
		}
	}
	function capturar2(id) {
		document.getElementById("disp").innerHTML="";
		cantidadp=document.getElementById('cantidadfinal'+id).value;
		productocodigo=document.getElementById('codigoproducto'+id).value;
		cantidadanterior=document.getElementById('cantidadproducto'+id).value;
		bodega=document.getElementById('bodega').value;
		var display=document.getElementById('editarfechas').checked;
		if (display) {
			fecha=document.getElementById('reservation').value;
			fecha=fecha.replace(" - ", "-");
			fecha=fecha.replace(" ", "");
			fecha=fecha.replace(" ", "");
			fecha=fecha.replace(" ", "");
			fecha=fecha.replace(" ", "");

			if (fecha=="") {
				alert("debe seleccionar un rango de fecha");
			}else if (bodega==0) {
				alert("debe seleccionar una bodega");
			}else {
				var url='confirmar1?productid='+productocodigo +"&cantidad="+cantidadp+"&tiempo="+fecha+"&bodega="+bodega;
				$('#disp').load("{!!url('"+url+"')!!}");
			
			}
		}else {
			fechadesde=document.getElementById('desde').value;
			fechadesde=fechadesde.replace(" ", "/");
			fechahasta=document.getElementById('hasta').value;
			fechahasta=fechahasta.replace(" ", "/");

			if (cantidadp-cantidadanterior!=0) {
				cantidadf=cantidadp-cantidadanterior;
				var url='confirmarproducto?productid='+productocodigo +"&cantidad="+cantidadf+"&desde="+fechadesde+"&hasta="+fechahasta+"&bodega="+bodega+"&id="+id;
				$('#disp').load("{!!url('"+url+"')!!}");
			}else{
				$('#aeditar'+id).addClass('disableda');
				$('#aeditar'+id).removeClass('disabledb');
			}

		}
	}
			function capturar3(id) {
			document.getElementById("disponible").innerHTML="";
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

			var num= $('.clonedInput').length;
			var product="";
			var cantidad="";
			for (i = 1; i < num+1; i++) {
				product = product + document.getElementById('produc'+i).value+"-";
				cantidad = cantidad + document.getElementById('cantidad'+i).value+"-";
			}
			var url='confirmar?productid='+product +"&cantidad="+cantidad+"&tiempo="+fecha+"&bodega="+bodega+"&caso=1";
			$("#disponible").load("{!!url('"+url+"')!!}");
		
			}
		}


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
	function habilitarfechas() {
		var display=document.getElementById('editarfechas').checked;
		if (display) {
			document.getElementById('reservation').disabled=false;
			document.getElementById('fecha_evento').disabled=false;
		}else {
			var datet=document.getElementById('fechajs').value;
		$('#datetimepicker1').datetimepicker({
				defaultDate: datet,
				format: 'DD/MM/YYYY hh:mm A'
				 });
			var lim=document.getElementById('reservation2').value;	
			document.getElementById('reservation').value=lim;
			document.getElementById('reservation').disabled=true;
			document.getElementById('fecha_evento').disabled=true;
}
	}


	$(document).ready(function() {
		var datet=document.getElementById('fechajs').value;
		$('#datetimepicker1').datetimepicker({
				defaultDate: datet,
				format: 'DD/MM/YYYY hh:mm A'
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
		$('#bodega').select2({
			language: {

				noResults: function() {

					return "No hay resultado";
				},
				searching: function() {

					return "Buscando..";
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
				$('#btnAdd').click(function() {
				var num     = $('.clonedInput').length;
				var newNum  = new Number(num + 1);
				var newElem = $('#input' + num).clone().attr('id', 'input' + newNum);
				newElem.children(':first').attr('id', 'name' + newNum).attr('class', 'width:60%').attr('id', 'produc' + newNum).attr('onchange','capturar3('+newNum+')');
				newElem.find("input").attr('id', 'cantidad' + newNum);
				newElem.find("span").remove();
				newElem.find("p").attr('id', 'disp' + newNum);
				newElem.find("select").select2().attr('onchange','capturar3('+newNum+')').attr('id', 'produc' + newNum);
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
