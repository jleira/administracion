<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="{{ asset('public/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('public/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />

</head>
  <body>
@foreach ($orden as $orden)
    
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
  <td  colspan="5" style="font-size: 15px;text-align:center;background-color: #792929;margin: 25px !important;padding: 15px;color: #fffff !important">EVENTO Y DETALLES</td>
          </tr>
          <tr >
          <td colspan="5" style="text-align:center;">CLIENTE:{{cliente($orden->cliente,1)}}</td>
          </tr>
          <tr >
            <td  colspan="5" style="text-align:center;">EMAIL: {{cliente($orden->cliente,2 )}}</td>
          </tr>
          <tr>
            <td   colspan="5" style="text-align:center;"">TELEFONO: {{cliente($orden->cliente,3 )}}</td>
          </tr>

          <tr>
            <td  colspan="5" style="text-align:center;">LUGAR DE RECEPCION: {{$orden->recepcion}}</td>
          </tr>

          <tr>
            <td colspan="5" style="text-align:center;">FECHA DE EVENTO: {{formatofecha($orden->fecha_evento)}}</td>
          </tr>

          <tr >
          <td colspan="5" style="text-align:center;">FECHA DE MONTAJE: {{formatofecha($orden->desde)}}</td>
          </tr>
          <tr >
          <td colspan="5" style="text-align:center;">FECHA Y HORA DE RECOGIDA: {{formatofecha($orden->hasta)}}</td>
          </tr>
          
{{buscarproductosexcel($orden->producto,$orden->cantidad,$orden->cliente)}}
<tr>
 <td colspan="5"></td>           
</tr>

@foreach ($factura as $factura)
    <tr>
      <td colspan="5" style="font-size: 15px;text-align:center;background-color: #792929;margin: 25px !important;padding: 15px;color: #fffff !important">RESUMEN</td>
    </tr>
    <tr>
      <th colspan="1" style="text-align:center">CANTIDAD</th>
      <th colspan="2" style="text-align:center">CATEGORIA</th>
      <th colspan="2" style="text-align:center">TOTAL</th>
    </tr>
    {{resumenexcel($orden->producto,$orden->cantidad,$orden->cliente,$factura->concepto_descuentos,$factura->descuentos,$factura->concepto_impuestos,$factura->impuestos,$factura->iva)}}
@endforeach
<tr>
 <td colspan="5"></td>           
</tr>
    <tr>
      <td colspan="5" style="font-size: 15px;text-align:center;background-color: #792929;margin: 25px !important;padding: 15px;color: #fffff !important">CONDICIONES COMERCIALES</td>
    </tr>
<tr>
  <td colspan="5" height="50" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('Las roturas, quemaduras, rasgaduras o pérdidas de los elementos en alquiler corren por cuenta del cliente y se cobrarán a precios de reposición de la empresa. Es su responsabilidad verificar todos los elementos contados y revisados al recibo y a la entrega. Depósito en efectivo o cheque a nombre de PALACIOS ZULUAGA E HIJOS por un valor de $ 500.000 el cual será consignado si después de 15 dias de realizado el evento no han cancelado las roturas, el saldo, si hubiere, será devuelto al cliente cuando lo solicite. El informe de roturas tendrá una demora de una semana aproximadamente por trámites internos',180)}}
  </td>
</tr>

<tr>
 <td colspan="5"></td>           
</tr>

<tr>
  <td colspan="5" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('Solamente se aceptarán cambios hasta ocho días antes del evento debido a lo complejo de nuestra operación.',180)}}
  </td>
</tr>
<tr>
 <td colspan="5"></td>           
</tr>

<tr>
  <td colspan="5" height="35" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('IMPORTANTE: muchos de nuestos items de decoracion, conllevan elementos en prestamo, para no incrementar el valor de los mismos, bases de vidrio, cilindros de parafina, vasitos de vela etc… por favor consulte con su asesor que elementos de su contrato van en prestamo,para tenerlos en cuenta al momento de la devolucion. de lo contrario seran
cobrados a precios de lista de nuestra compañia.
',180)}}
  </td>
</tr>

<tr>
 <td colspan="5"></td>           
</tr>

<tr>
  <td colspan="5" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('Una vez cerrado el contrato elaboraremos un centro de mesa y prepararemos una prueba del menú para su aprobación',180)}}
  </td>
</tr>
<tr>
 <td colspan="5"></td>           
</tr>

<tr>
  <td colspan="5" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('Forma de pago : 30% de anticipo contra verificación de disponibilidad y el 70% restante debe estar cancelado ocho días antes del evento.',180)}}
  </td>
</tr>
<tr>
 <td colspan="5"></td>           
</tr>

<tr>
  <td colspan="5" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('La vajilla, cristalería y cubiertería debe ser devuelta lavada, en caso contrario, será facturado el lavado según lista de precios.',180)}}
  </td>
</tr>
<tr>
 <td colspan="5"></td>           
</tr>

<tr>
  <td colspan="5" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('Las carpas no deberán ser movidas ni trasladadas del sitio de instalación, estos pueden generar daños en la estructura que corren por cuenta del cliente en su totalidad.',180)}}
  </td>
</tr>
<tr>
 <td colspan="5"></td>           
</tr>

<tr>
  <td colspan="5" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('No es permitido poner en las carpas pendones, serpentinas o papeles de colores que suelten tinta, estos pueden machar las lonas de las carpas. La lluvia cósmica manchan las salas y el costo del tapizado corre por cuenta del cliente',180)}}
  </td>
</tr>
<tr>
 <td colspan="5"></td>           
</tr>

<tr>
  <td colspan="5" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('El valor del transporte podrá variar de acuerdo a los elementos contratados o de requerir montajes o desmontajes nocturnos.',180)}}
  </td>
</tr>
<tr>
 <td colspan="5"></td>           
</tr>

<tr>
  <td colspan="5" style="font-size: 8; font-style:italic;" valign="top" >{{dividirtexto('De sobrepasar el número de horas contratadas a los meseros, se cancelarán directamente a ellos las horas adicionales convenidas entre las dos partes',180)}}
  </td>
</tr>



</tbody>


    </table>

@endforeach

  </body>
  </html>
