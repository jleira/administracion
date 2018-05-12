<!DOCTYPE html>
<html>
<head>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="{{ asset('public/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />

</head>
  <body>
		@if($decide==0)
    <div class="col-lg-12">
      <div class="box box-primary">
          <div class="box-header">
                  <div class="box-header" style="text-align: center">
        <img src="img/logo.png">
        </div>
          <h3 class="box-title">TODOS LOS PRODUCTOS</h3>
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

    @else
    <div class="col-lg-12">
      <div class="box box-primary">
          <div class="box-header">
          <h3 class="box-title">{{validarbodega($bodegas)}}</h3>
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
                          <td>{{$totales[$i]}}</td>
                          <td>{{$reservados[$i]}}</td>
                          <td>0</td>
                          <td>{{$totales[$i]-$reservados[$i]}}</td>
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
@endif
  </body>
  </html>
