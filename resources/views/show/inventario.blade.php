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
		@if($decide==0)
    <div class="col-lg-12">
      <div class="box box-primary">
          <div class="box-header">
          <h3 class="box-title">TODOS LOS PRODUCTOS</h3>
					<form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('verinventario') }}">
					{{ csrf_field() }}
					@php
$productosf= ((array) $productos);
$productosff=implode(",", $productosf);
$totalf=implode(",", (array) $totales);
$reservadosf=implode(",",(array) $reservados);
$bodegasf=implode(",",(array) $bodegas);
@endphp
<input type="hidden" name="productos" value="{{$productosff}}">
<input type="hidden" name="total" value="{{$totalf}}">
<input type="hidden" name="bodegas" value="{{$bodegasf}}">
<input type="hidden" name="reservado" value="{{$reservadosf}}">
<input type="hidden" name="iteraciones" value="{{$iteracions}}">
<input type="hidden" name="bodegasc" value="{{$bodegasc}}">

<button type="submit" class="btn btn-danger pull-right" name="button" value="1" formtarget="_blank">Descargar(PDF)</button>
<button type="submit" class="btn btn-success pull-right" name="button" value="3" formtarget="_blank">Descargar(EXCEL)</button>

					</form>
					</div>

                  <!-- /.box-header -->
            <div class="box-body">
              <table id="productos" class=" productos table table-bordered table-striped">
              <thead>
                <tr>
                        <th>Codigo - Nombre</th>
                        <th>Total</th>
                        <th>Reservados</th>
                        <th>Alquilados</th>
                        <th>disponible</th>
                      </tr>
                      </thead>
                      <tbody>
                        @for ($i = 0; $i < $iteracions; $i++)
                        @php
  $total=0;
  $reservado=0;
  @endphp
                        <tr>
                          <td>{{$productos[$i]}} - {{validarproductoporcodigo($productos[$i])}}</td>
                          <td>
                              @for ($j = 0; $j < $bodegasc; $j++)
                              @php
                                $total=$total+$totales[($i*$bodegasc)+$j]
                              @endphp
                                @endfor
                            {{$total}}
                            </td>
                          <td>
                            @for ($j = 0; $j < $bodegasc; $j++)
                              @php
                              $reservado=$reservado+$reservados[($i*$bodegasc)+$j]
                              @endphp
                          @endfor
                        {{$reservado}}</td>
                          <td>0</td>

                          <td>
                            {{$total-$reservado}}
                          </tr>
                          @endfor
                      </tbody>
                      <tfoot>
                      <tr>
                        <th>Nombre</th>
                        <th>Codigo</th>
                        <th>cantidad total</th>
                        <th>reservada</th>
                        <th>disponible</th>
                       </tr>
                       </tfoot>
                       </table>

                     </div>
                     </div>
                   </div>
                   <br>

    @elseif($decide==1)
    @for ($j = 0; $j < $bodegasc; $j++)
    <div class="col-lg-12">
      <div class="box box-primary">
          <div class="box-header">
          <h3 class="box-title">{{validarbodega($bodegas[$j])}}</h3>
					<form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('verinventario') }}">
					{{ csrf_field() }}
					@php
					$productosf= ((array) $productos);
					$productosff=implode(",", $productosf);
					for ($m = 0; $m < $bodegasc; $m++){
						for ($n = 0; $n < $iteracions; $n++){
							$totalesf[$n]=$totales[($n*$bodegasc)+$m];
							$reservadosff[$n]=$reservados[($n*$bodegasc)+$m];
							$alquilados[$n]=$totales[($n*$bodegasc)+$m]-$reservados[($n*$bodegasc)+$m];
						}
					}
					$totalf=implode(",", $totalesf);
					$reservadosf=implode(",", $reservadosff);
					$alquiladosf=implode(",", $alquilados);
@endphp
<input type="hidden" name="alquilados" value="{{$alquiladosf}}">
<input type="hidden" name="reservado" value="{{$reservadosf}}">
<input type="hidden" name="total" value="{{$totalf}}">
<input type="hidden" name="bodegas" value="{{$bodegas[$j]}}">
<input type="hidden" name="productos" value="{{$productosff}}">
<input type="hidden" name="iteraciones" value="{{$iteracions}}">
<button type="submit" class="btn btn-success pull-right" name="button" value="2">Descargar</button>
					</form>
          </div>
                  <!-- /.box-header -->
            <div class="box-body">
              <table id="productos" class=" productos table table-bordered table-striped">
              <thead>
                <tr>
                        <th>Codigo - Nombre</th>
                        <th>Total</th>
                        <th>Reservados</th>
                        <th>Alquilados</th>
                        <th>disponible</th>
                      </tr>
                      </thead>
                      <tbody>
                        @for ($i = 0; $i < $iteracions; $i++)
                        <tr>
                          <td>{{$productos[$i]}} - {{validarproductoporcodigo($productos[$i])}}</td>
                          <td>{{$totales[($i*$bodegasc)+$j]}}</td>
                          <td>{{$reservados[($i*$bodegasc)+$j]}}</td>
                          <td>0</td>
                          <td>{{$totales[($i*$bodegasc)+$j]-$reservados[($i*$bodegasc)+$j]}}</td>
                          </tr>
                          @endfor
                      </tbody>
                      <tfoot>
                      <tr>
                        <th>Nombre</th>
                        <th>Codigo</th>
                        <th>cantidad total</th>
                        <th>reservada</th>
                        <th>disponible</th>
                       </tr>
                       </tfoot>
                       </table>

                     </div>
                     </div>
                   </div>
                   <br>
                   @endfor
@else

@for ($j = 0; $j < $cat; $j++)
<div class="col-lg-12">
	<div class="box box-primary">
			<div class="box-header">
			<h3 class="box-title">{{validarlista($cate[$j],4)}}</h3>
			<form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('verinventario') }}">
			{{ csrf_field() }}
			@php
			$productosf= ((array) $productos);
			$productosff=implode(",", $productosf);
			for ($m = 0; $m < $cat; $m++){
				for ($n = 0; $n < $iteracions; $n++){
					$totalesf[$n]=$totales[($n*$cat)+$m];
					$reservadosff[$n]=$reservados[($n*$cat)+$m];
					$alquilados[$n]=$totales[($n*$cat)+$m]-$reservados[($n*$cat)+$m];
				}
			}
			$totalf=implode(",", $totalesf);
			$reservadosf=implode(",", $reservadosff);
			$alquiladosf=implode(",", $alquilados);
@endphp
<input type="hidden" name="alquilados" value="{{$alquiladosf}}">
<input type="hidden" name="reservado" value="{{$reservadosf}}">
<input type="hidden" name="total" value="{{$totalf}}">
<input type="hidden" name="bodegas" value="{{$bodegas[$j]}}">
<input type="hidden" name="productos" value="{{$productosff}}">
<input type="hidden" name="iteraciones" value="{{$iteracions}}">
<button type="submit" class="btn btn-success pull-right" name="button" value="2">Descargar</button>
			</form>
			</div>
							<!-- /.box-header -->
				<div class="box-body">
					<table id="productos" class=" productos table table-bordered table-striped">
					<thead>
						<tr>
										<th>Codigo - Nombre</th>
										<th>Total</th>
										<th>Reservados</th>
										<th>Alquilados</th>
										<th>disponible</th>
									</tr>
									</thead>
									<tbody>
										@for ($i = 0; $i < $iteracions; $i++)
										<tr>
											<td>{{$productos[$i]}} - {{validarproductoporcodigo($productos[$i])}}</td>
											<td>{{$totales[($i*$cat)+$j]}}</td>
											<td>{{$reservados[($i*$cat)+$j]}}</td>
											<td>0</td>
											<td>{{$totales[($i*$cat)+$j]-$reservados[($i*$cat)+$j]}}</td>
											</tr>
											@endfor
									</tbody>
									<tfoot>
									<tr>
										<th>Nombre</th>
										<th>Codigo</th>
										<th>cantidad total</th>
										<th>reservada</th>
										<th>disponible</th>
									 </tr>
									 </tfoot>
									 </table>

								 </div>
								 </div>
							 </div>
							 <br>
							 @endfor



@endif



</div>
<script type="text/javascript">
$(function () {
	$(".productos").DataTable({
		"language": {
					"lengthMenu": "Mostrar _MENU_ productos por pagina",
					"zeroRecords": "No hay productos registrados",
					"info": "Pagina _PAGE_ de _PAGES_",
					"infoEmpty": "",
					"infoFiltered": "(Filtrado de _MAX_ bodegas )",
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
