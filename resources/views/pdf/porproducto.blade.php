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
          <img src="{{logo()}}">
        </div>
        @if($bodega==0)
        <h3 style="    text-align: center;background-color:#792929;color:white;width: 100%;color: white;padding: 5px;" class="box-title">Todas las bodegas incluidas - {{formatofecha(hoy())}}<br> {{formatofecha($fecha_inicio)}} / {{formatofecha($fecha_fin)}}
        </h3>
        @else
                <h3 style="    text-align: center;background-color:#792929;color:white;width: 100%;color: white;padding: 5px;" class="box-title">{{validarbodega($bodega)}} - {{formatofecha(hoy())}}<br>{{formatofecha($fecha_inicio)}} / {{formatofecha($fecha_fin)}}</h3>

        @endif
      </div>

      <div class="box-body">
        <table style="width: 100%">
          <thead>
            <tr>
              <th>Cod</th>
              <th>Nombre</th>
              <th>Cant</th>
              <th>Reserv.</th>
              <th>disp</th>
              <th>P. compra</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody style="font-size: 8">
            @foreach($productos as $producto)
            <tr>
              <td>{{$producto->codigo}}</td>
              <td>{{$producto->nombre}}</td>
              @php
              $cantidad=totalp($producto->codigo,$bodega);
              $reservados=cant_reservada($producto->codigo,$bodega,$fecha_inicio,$fecha_fin);
              @endphp
              <td>{{$cantidad}}</td>
              <td>{{$reservados}}</td>
              @if($cantidad-$reservados < 0)
              <td style="color: red" >{{$cantidad-$reservados}}</td>
              @else
              <td >{{$cantidad-$reservados}}</td>
              @endif
              <td>{{formatoprecio($producto->vl_unitario)}}</td>
              <td>{{formatoprecio($cantidad*$producto->vl_unitario)}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>
  <br>

</body>
</html>
