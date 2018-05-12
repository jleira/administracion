<!DOCTYPE html>
<html>
<head>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link href="{{ asset('public/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />

</head>
<body>
  <div class="col-lg-12">
    <div class="box ">
       <div class="box-body">
              <table style="width: 100%">
        <thead>
        <tr>
              <th style="background-color: #ffffff;" width="15" ></th>
              <th style="background-color: #ffffff;" width="15"></th>
              <th style="background-color: #ffffff;" width="40" ><img src="{{logoexcel()}}"></th>
              <th style="background-color: #ffffff;" width="15" ></th>
              <th style="background-color: #ffffff;" width="15"></th>
              <th style="background-color: #ffffff;" width="15" ></th>
              <th  width="15"></th>
            </tr> 
        </thead>
        <tbody style="font-size: 8">
        @if($bodega==0)
        <tr><td  colspan="7"  style="font-size: 15px;text-align:center;background-color: #792929;margin: 25px !important;padding: 15px;color: #fffff !important"><h3>Todas las bodegas incluidas - {{formatofecha(hoy())}}</h3></td></tr>
        <tr><td  colspan="7" style="font-size: 15px;text-align:center;background-color: #792929;margin: 25px !important;padding: 15px;color: #fffff !important">        <h3 >{{formatofecha($fecha_inicio)}} / {{formatofecha($fecha_fin)}}</h3></td></tr>
        @else
        <tr>
            <td  colspan="7"  style="font-size: 15px;text-align:center;background-color: #792929;margin: 25px !important;padding: 15px;color: #fffff !important"><h3>{{validarbodega($bodega)}} - {{formatofecha(hoy())}}</h3>
        </td></tr>
        <tr><td colspan="7" style="font-size: 15px;text-align:center;background-color: #792929;margin: 25px !important;padding: 15px;color: #fffff !important">        <h3 >{{formatofecha($fecha_inicio)}} / {{formatofecha($fecha_fin)}}</h3></td></tr>
        @endif

        </tbody>
        </table>




        <table style="width: 100%">
         <thead>    
                  <tr>
            <th  width="10" >Codigo</th>
            <th  width="50" >Nombre</th>
            <th  width="10" >Cantidad</th>
            <th width="15" >Reservados</th>
            <th  width="11" >disponibles</th>
            <th  width="17">P. compra</th>
            <th  width="17">Total</th>
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
              <td>${{$producto->vl_unitario}}</td>
              <td>${{$cantidad*$producto->vl_unitario}}</td>
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
