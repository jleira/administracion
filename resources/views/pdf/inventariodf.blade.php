<!DOCTYPE html>
<html>
<head>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="{{ asset('public/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />

</head>
  <body>
    <div class="col-lg-12">
      <div class="box ">
          <div class="box-header">
                  <div class="box-header" style="text-align: center">
        <img src="img/logo.png">
        </div>
          <h3 style="    text-align: center;background-color: #792929;width: 100%;color: white;padding: 5px;" class="box-title">TODOS LOS PRODUCTOS</h3>
          </div>
                  <!-- /.box-header -->
            <div class="box-body">
              <table style="width: 100%">
              <thead>
                <tr>
                        <th>Codigo - Nombre</th>
                        <th>Total</th>
                        <th>Reservados</th>
                        <th>disponible</th>
                        <th>Precio de compra</th>
                      </tr>
                      </thead>
                      <tbody style="font-size: 8">
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
                         

                          <td>
                            {{$total-$reservado}}</td>
                            <td>$ {{number_format(valordecompra($productos[$i]))}}</td>
                          </tr>
                          @endfor
                      </tbody>
                       </table>

                     </div>
                     </div>
                   </div>
                   <br>

  </body>
  </html>
