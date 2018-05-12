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
           <h3 class="box-title">Registrar Reservacion </h3>
         </div>
       <div class="box-body">
         <form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('registrarreserva') }}">
         {{ csrf_field() }}
				 <div class="form-group">
					 <label>Tiempo de reservacion</label>

							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" name="tiempo" value="{{old('tiempo')}}" class="form-control pull-right" id="reservation">

							</div>
						 @if ($errors->has('tiempo') )
											<p style="color:red;margin:0px">{{ $errors->first('tiempo') }}</p>
										 @endif
		<!-- /.input group -->
	</div>
	@if (Auth::user()->id_perfil==1)
	<div class="form-group" id="bodega2">
		<label >Bodega</label>
		<select class="form-control select2" id="bodega" style="width: 100%;"  name="bodega_item">
			<option value="0">Seleccione bodega</option>
			@foreach ($bodegas as $bodega)
			<option value="{{$bodega->id}}">{{$bodega->nombre}}</option>
			@endforeach
			 </select>
	</div>
	@else
	<input type="hidden" name="bodega_item" id="bodega" value="{{Auth::user()->bodega_id}}">
	@endif
       <div class="form-group">
         <label for="">Seleccione el producto - cantidad</label>
           <div id="input1" class="clonedInput" style="padding:2px">
						 <input value="1" type="number" name="cantidad[]" id="cantidad1" style="border-radius: 10px 0px 0px 10px;padding: 2px;border-style: groove;vertical-align: top;text-align: center;width: 50px;" />
             <select class="form-control select2" onchange="capturar(1)"  style="width: 80%;" name="producto[]" id="produc1" >
               @foreach ($productos as $producto)
               <option value="{{$producto->codigo}}">{{$producto->codigo}} - {{$producto->nombre}}</option>
               @endforeach
             </select>

							 @if ($errors->has('producto[]') )
												<p style="color:red;margin:0px">{{ $errors->first('producto[]') }}</p>
											 @endif
           </div>
					 @if ($errors->has('cantidad') )
										<p style="color:red;margin:0px">{{ $errors->first('cantidad') }}</p>
									 @endif
           <div style="padding-top:4px" >
               <a href="#" id="btnAdd" class="btn btn-primary btn-xs" value="+">+</a>
           </div>
       </div>


<div class="form-group">

<a href="javascript: submitform()" name="tiene" >Buscar disponibiliad<a/>
<div class="form-group">
  <label for="">Cliente</label>
      <select class="form-control select2" style="width: 100%;" name="cliente" id="cliente">
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
    <button type="submit" class="btn btn-primary" name="button">Registrar</button>
     </div>
</form>

       </div>
       </div>
       </div>
       </div>
			 <div class="col-lg-6" id="disponibles">

			 </div>
      </div>


<script src="plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">
$('#produc1').select2();
function capturar(id) {
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
	var num     = $('.clonedInput').length;
	var product="";
	var cantidad="";
	for (i = 1; i < num+1; i++) {
    product = product + document.getElementById('produc'+i).value+"-";
		cantidad = cantidad + document.getElementById('cantidad'+i).value+"-";
}
fecha=document.getElementById('reservation').value;
fecha=fecha.replace(" - ", "-");
bodega=document.getElementById('bodega').value;
var url='confirmar?productid='+product +"&cantidad="+cantidad+"&tiempo="+fecha+"&bodega="+bodega;
if (bodega==0) {
alert('seleccione una bodega');
}else {
$("#disponibles").load("{!!url('"+url+"')!!}");
}
}
	  $(document).ready(function() {
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
        $('#btnAdd').click(function() {
            var num     = $('.clonedInput').length;
            var newNum  = new Number(num + 1);
            var newElem = $('#input' + num).clone().attr('id', 'input' + newNum);
          newElem.children(':first').attr('id', 'name' + newNum).attr('class', 'width:60%').attr('id', 'produc' + newNum).attr('onchange', 'capturar(' + newNum+')');
					newElem.find("input").attr('id', 'cantidad' + newNum);
					newElem.find("p").attr('id', 'disp' + newNum);
					newElem.find("span").remove();
          newElem.find("select").select2();
            $('#input' + num).after(newElem);

						$("#btnless").removeAttr("disabled");
    });
		$('#btnless').click(function() {
				var num     = $('.clonedInput').length;
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
