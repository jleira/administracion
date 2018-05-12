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
				<strong>Eroor! </strong> {{Session::get('error_message')}}
			</div>
			@endif

	 <div class="col-md-4">
	       <div class="box box-primary">
	          <div class="box-header with-border">
	            <h3 class="box-title">Registrar Entrada O Salida De Productos </h3>
	          </div>
	          <form role="form" enctype="multipart/form-data" method="POST" action="{{ url('registrarmovimiento') }}">
	          {{ csrf_field() }}
	          					<input type="hidden" value="0,0" name="posicion" id="posicion">

	          <div class="box-body">
							<div class="form-group">
			          <label for="">Seleccione el producto - cantidad</label>
			            <div id="input1" class="clonedInput" style="padding:2px">
			              <select class="form-control select2"  style="width: 80%;" name="producto[]" id="produc1" >
			                @foreach ($productos as $producto)
			                <option value="{{$producto->codigo}}">{{$producto->codigo}} - {{$producto->nombre}}</option>
			                @endforeach
			              </select>
			                <input value="1" type="number" name="cantidad[]" id="cantidad1" style="border-radius: 0px 10px 10px 0px;padding: 2px;border-style: groove;vertical-align: top;text-align: center;width: 50px;" />
			 							 @if ($errors->has('producto[]') )
			 												<p style="color:red;margin:0px">{{ $errors->first('producto[]') }}</p>
			 											 @endif
			            </div>
			 					 @if ($errors->has('cantidad') )
			 										<p style="color:red;margin:0px">{{ $errors->first('cantidad') }}</p>
			 									 @endif
			            <div style="padding-top:4px" >
			                <a href="#" id="btnAdd" class="btn btn-primary btn-xs" value="+">+</a>
			                <a  id="btnless" class="btn btn-danger btn-xs" value="-" disabled>-</a>
			            </div>
			        </div>
							<div class="form-group">
									@if (Auth::user()->id_perfil==1)
									<label >item</label>
									<select class="form-control select2" id="item" style="width: 100%;" onchange="capturar()"  name="item">
										<option value="{{old('item')}}">{{validarlista(old('item'),1)}}</option>

									@foreach ($items as $item)
									<option value="{{$item->valor_lista}}">{{$item->valor_item}}</option>
									@endforeach
									@else
									<label >item</label>
									<select class="form-control select2" id="item" style="width: 100%;" name="item">
										<option value="{{old('item')}}">{{validarlista(old('item'),1)}}</option>
										@foreach ($items as $item)
									@if($item->valor_lista!=3)
									<option value="{{$item->valor_lista}}">{{$item->valor_item}}</option>
									@endif
									@endforeach
									@endif

									 </select>
				@if ($errors->has('item') )
								 <p style="color:red;margin:0px">{{ $errors->first('item') }}</p>
								@endif
							</div>

							<div class="form-group" id="bodega" style="display:none">
								<label >Bodega de donde sale</label>
								<select class="form-control select2" id="item" style="width: 100%;"   name="bodega_salida">
									<option value="0">Seleccione bodega</option>
									@foreach ($bodegas as $bodega)
									<option value="{{$bodega->id}}">{{$bodega->nombre}}</option>
									@endforeach
									 </select>
									 <label >Bodega donde entra</label>
									 <select class="form-control select2" id="item" style="width: 100%;"  name="bodega_entrada">
										 <option value="0">Seleccione bodega</option>
										 @foreach ($bodegas as $bodega)
										 <option value="{{$bodega->id}}">{{$bodega->nombre}}</option>
										 @endforeach
											</select>
							</div>


							<div class="form-group" id="bodega2" style="display:none">
								<label >Bodega</label>
								<select class="form-control select2" id="item" style="width: 100%;"  name="bodega_item">
									<option value="0">Seleccione bodega</option>
									@foreach ($bodegas as $bodega)
									<option value="{{$bodega->id}}">{{$bodega->nombre}}</option>
									@endforeach
									 </select>
							</div>

						<div class="form-group">
					<label for="exampleInput">Descripcion</label>
					<textarea type="text" class="form-control" rows="4" name="descripcion" id="exampleInputEmail1" placeholder="">{{ old('descripcion')}}</textarea>
					@if ($errors->has('descripcion') )
									 <p style="color:red;margin:0px">{{ $errors->first('descripcion') }}</p>
									@endif
								</div>

		 					<div class="box-footer" id="registrar">
						@if (validarcertificado())

						@else
						<button type="submit" class="btn btn-primary">Registrar</button>
						@endif
					</div>
					 </form>
	      	</div>
	      </div>
	      </div>

						<div class="col-lg-8">
								<div class="box box-primary">
								            <div class="box-header">
								              <h3 class="box-title">productos</h3>
								            </div>
								            <!-- /.box-header -->
								            <div class="box-body">
								              <table id="productos" class="table table-responsive table-bordered table-striped">
								                <thead>
								                <tr>
								                  <th>Nombre</th>
								                  <th>Codigo</th>
													<th>Descripcion</th>
								                  <th>V. Compra</th>
								                  <th>V. Mayorista</th>
								                  <th>V. Minorits</th>
								                </tr>
								                </thead>
								                <tbody>
																@foreach ($productos as $producto)
																	<tr>
																		<td>{{$producto->nombre}}</td>
																		<td>{{$producto->codigo}}</td>
																		<td>{{$producto->descripcion}}</td>
																		<td>$ {{number_format($producto->vl_unitario)}}</td>
																		<td>$ {{number_format($producto->vl_mayorista)}}</td>
																		<td>$ {{number_format($producto->vl_minorista)}}</td>
																	</tr>
																@endforeach

																</tbody>
													
																 </table>
															 </div>
															 </div>
														 </div>
	

	</div>

	<script src="public/plugins/select2/select2.full.min.js"></script>
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


	 $("#produc").select2();
	  $("#item").select2();
		function capturar() {
		  verbodegas = document.getElementById("bodega");
			verbodegas2 = document.getElementById("bodega2");
		  var item = document.getElementById('item');
		  if(item.options[item.selectedIndex].value==3){
		verbodegas.style.display='block';
		verbodegas2.style.display='none';
	}else{
		verbodegas.style.display='none';
		verbodegas2.style.display='block';
	}
	if (item.options[item.selectedIndex].value=="") {
		verbodegas.style.display='none';
		verbodegas2.style.display='none';
	}

		}
		$('#produc1').select2();

		$('#btnAdd').click(function() {
				var num     = $('.clonedInput').length;
				var newNum  = new Number(num + 1);
				var newElem = $('#input' + num).clone().attr('id', 'input' + newNum);
			newElem.children(':first').attr('id', 'name' + newNum).attr('class', 'width:60%').attr('id', 'produc' + newNum);
			newElem.find("input").attr('id', 'cantidad' + newNum);
			newElem.find("span").remove();
			newElem.find("select").select2();
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

	$(function () {
		$("#productos").DataTable({
			"language": {
						"lengthMenu": "Mostrar _MENU_ productos por pagina",
						"zeroRecords": "No hay productos registrados",
						"info": "Pagina _PAGE_ de _PAGES_",
						"infoEmpty": "",
						"infoFiltered": "(Filtrado de _MAX_ productos )",
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
