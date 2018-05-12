 <{!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="{{ asset('public/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('public/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />

</head>
  <body>
    
    <table style="width:200% !important;">
    <thead><tr>
    <th style="background-color: #ffffff;" width="20" ></th>
    <th style="background-color: #ffffff;" width="20"></th>
    <th style="background-color: #ffffff;" width="40" ><img src="public/img/logo.png"></th>
    <th style="background-color: #ffffff;" width="20"></th>
    <th  width="20"></th>
    </tr> 
</thead>

<tbody>
<tr>
  <td  colspan="5" style="font-size: 15px;text-align:center;background-color: #792929;margin: 25px !important;padding: 15px;color: #fffff !important">Productos</td>
          </tr>
          <tr >
          <td colspan="5" style="text-align:center;"></td>
          </tr>
          <tr>
            <td><strong>Codigo - Nombre</strong> </td>
            <td><strong>Total</strong> </td>
            <td><strong>Reservados</strong></td>
            <td><strong>Disponibles</strong></td>
            <td><strong>Precio de compra</strong></td>
          </tr>
                    <tr >
          <td colspan="5" style="text-align:center;"></td>
          </tr>

              @for ($i = 0; $i < $iteracions; $i++)
          <tr >
           <td>{{$productos[$i]}} - {{validarproductoporcodigo($productos[$i])}}</td>
            <td>{{$totales[$i]}}</td>
            <td>{{$reservados[$i]}}</td>
            <td>{{$totales[$i]-$reservados[$i]}}</td>
            <td>$ {{number_format(valordecompra($productos[$i]))}}</td>
           </tr>
              @endfor

</tbody>


    </table>


  </body>
  </html>
