<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />

</head>
<style media="screen">
  p{
    font-size: 10px !important;
  }
  td{
    font-size: 10px !important;
  }
  th{
    font-size: 12px !important;
  }
</style>
<body>
  @foreach ($orden as $orden)
  <div class="col-lg-12">
    <div class="box box-danger">
      <div class="box-header" style="text-align: center">
        <img src="img/logo.png">
      </div>
      <div class="box-body">

        <table style="width:100%;margin: 10px 0px 10px 0px;">
          <thead style="background-color:#792929;color:white">
            <tr>
            <th colspan="3" style="text-align:center;padding:4px;border-radius: 0px;FONT-SIZE: 12px;">EVENTO Y DETALLES</th>
            </tr>
          </thead>
          <tbody>
           <tr style="border-style: hidden;">
            <td colspan="3" style="text-align:center;">CLIENTE: {{cliente($orden->cliente,1)}}</td>
          </tr>
          <tr style="border-style: hidden;">
            <td colspan="3" style="text-align:center;">EMAIL: {{cliente($orden->cliente,2 )}}</td>
          </tr>
          <tr style="border-style: hidden;">
            <td colspan="3" style="text-align:center;">TELEFONO: {{cliente($orden->cliente,3 )}}</td>
          </tr>

          <tr style="border-style: hidden;">
            <td  colspan="3" style="text-align:center;">LUGAR DE RECEPCION: {{$orden->recepcion}}</td>
          </tr>

          <tr style="border-style: hidden;">
            <td  colspan="3" style="text-align:center;">FECHA DE EVENTO: {{formatofecha($orden->fecha_evento)}}</td>
          </tr>

          <tr style="border-style: hidden;">
            <td  colspan="3" style="text-align:center;">FECHA DE MONTAJE: {{formatofecha($orden->desde)}}</td>
          </tr>
          <tr style="border-style: hidden;">
            <td  colspan="3" style="text-align:center;">FECHA Y HORA DE RECOGIDA: {{formatofecha($orden->hasta)}}</td>
          </tr>
        </tbody>
      </table>

      {{buscarproductos($orden->producto,$orden->cantidad,$orden->cliente)}}

      @foreach ($factura as $factura)
      <table style="width:100%;margin: 10px 0px 10px 0px;">
        <thead style="background-color:#792929;color:white">
          <tr>
            <th colspan="3" style="text-align:center;padding:4px;border-radius: 0px;FONT-SIZE: 12px;">RESUMEN</th>
          </tr>

        </thead>

        <tbody>
          <tr>
            <th style="text-align:center">CANTIDAD</th>
            <th style="text-align:center">CATEGORIA</th>
            <th style="text-align:center">TOTAL</th>
          </tr>
          {{resumen($orden->producto,$orden->cantidad,$orden->cliente,$factura->concepto_descuentos,$factura->descuentos,$factura->concepto_impuestos,$factura->impuestos,$factura->iva)}}
        </tbody>
      </table>

      @endforeach

      <div class="panel panel-danger" style="padding:10px 10px 10px 0px;">
        <div class="panel-heading" style='text-align:center;background-color:#792929;color:white;padding:4px;border-radius: 0px;FONT-SIZE: 12px'>CONDICIONES COMERCIALES</div>
        <div class="panel-body">
          <p>
            Las roturas, quemaduras, rasgaduras o pérdidas de los elementos en alquiler corren por cuenta del cliente y se cobrarán a precios de reposición de la empresa. Es su responsabilidad verificar todos los elementos contados y revisados al recibo y a la entrega. Depósito en efectivo o cheque a nombre de PALACIOS ZULUAGA E HIJOS por un valor de $ 500.000 el cual será consignado si después de 15 dias de realizado el evento no han cancelado las roturas, el saldo, si hubiere, será devuelto al cliente cuando lo solicite. El informe de roturas tendrá una demora de una semana aproximadamente por trámites internos.
          </p>

          <p>
            Solamente se aceptarán cambios hasta ocho días antes del evento debido a lo complejo de nuestra operación.
          </p>

          <p>
            IMPORTANTE: muchos de nuestos items de decoracion, conllevan elementos en prestamo, para no incrementar el valor de los mismos, bases de vidrio, cilindros de parafina, vasitos de vela etc…  por favor consulte con su asesor que elementos de su contrato van en prestamo,para tenerlos en cuenta al momento de la devolucion. de lo contrario seran cobrados a precios de lista de nuestra compañia.
          </p>

          <p>
            Una vez cerrado el contrato elaboraremos un centro de mesa y prepararemos una prueba del menú para su aprobación.
          </p>

          <p>
            Forma de pago : 30% de anticipo contra verificación de disponibilidad y el 70% restante debe estar cancelado ocho días antes del evento.
          </p>

          <p>La vajilla, cristalería y cubiertería debe ser devuelta lavada, en caso contrario, será facturado el  lavado según lista de precios.
          </p>

          <p>
            Las carpas no deberán ser movidas ni trasladadas del sitio de instalación,  estos pueden generar daños en la estructura que corren por cuenta del cliente en su totalidad.
          </p>
          <p>
            No es permitido poner en las carpas pendones, serpentinas o papeles de colores que suelten tinta, estos pueden  machar las lonas de las carpas. La lluvia cósmica manchan las salas y el costo del tapizado corre por cuenta del cliente.
          </p>

          <p>
            El valor del transporte podrá variar de acuerdo a los elementos contratados o de requerir montajes o desmontajes nocturnos.
          </p>

          <p>
            De sobrepasar el número de horas contratadas a los meseros, se cancelarán directamente a ellos las horas adicionales convenidas entre las dos partes.
          </p>

        </div>
      </div>
    </div>
  </div>
</div>
@endforeach

</body>
</html>
