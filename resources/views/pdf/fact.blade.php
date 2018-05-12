<!DOCTYPE html>
<html>
<head>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

<link href="{{logo()}}" rel="icon" type="image/x-icon" />
<style type="text/css">
@page {
            margin-top: 0.1em;
            margin-left: 0.1em;
        }
tr td {
  padding: 0px;
  margin: 0px;
}


</style>
</head>
<body>
    <div style="width: 100%;float: left;position: relative;">
    @if($fact==1)
    <img style="width: 100%" src="{{url('storage/app/public/fct.png')}}">    

@else
      <img style="width: 100%" src="{{url('storage/app/public/blanco.png')}}">

@endif

<h6 style="position: absolute;top:108px;left:110px;color: #272727">{{cliente($reserva[0]->cliente,1)}}</h6>
<h6 style="position: absolute;top:108px;left:370px;color: #272727">{{cliente($reserva[0]->cliente,6)}}</h6>
<h6 style="position: absolute;top:108px;left:550px;color: #272727">{{cliente($reserva[0]->cliente,3)}}</h6>


<!-- fecha factura -->
<h6 style="position: absolute;top:134px;left:122px;color: gray;font-size: 13px;font-weight: lighter;">{{fecha_($reserva[0]->fecha_evento,1)}}</h6>
<h6 style="position: absolute;top:134px;left:150px;color: gray;font-size: 13px;font-weight: lighter;">{{fecha_($reserva[0]->fecha_evento,2)}}</h6>
<h6 style="position: absolute;top:134px;left:175px;color: gray;font-size: 13px;font-weight: lighter;">{{fecha_($reserva[0]->fecha_evento,3)}}</h6>
<!-- end fecha factura -->


<!-- vencimiento -->
<h6 style="position: absolute;top:134px;left:310px;color: gray;font-size: 13px;font-weight: lighter;">{{fecha_($reserva[0]->fecha_evento,1)}}</h6>    
<h6 style="position: absolute;top:134px;left:336px;color: gray;font-size: 13px;font-weight: lighter;">{{fecha_($reserva[0]->fecha_evento,2)}}</h6>
 <h6 style="position: absolute;top:134px;left:368px;color: gray;font-size: 13px;font-weight: lighter;">{{fecha_($reserva[0]->fecha_evento,3)}}</h6>
<!-- end vencimiento -->


<!-- fecha factura -->
<h6 style="position: absolute;top:134px;left:514px;color: gray;font-size: 13px;font-weight: lighter;">{{fecha_($reserva[0]->fecha_evento,1)}}</h6>    
<h6 style="position: absolute;top:134px;left:544px;color: gray;font-size: 13px;font-weight: lighter;">{{fecha_($reserva[0]->fecha_evento,2)}}</h6>
<h6 style="position: absolute;top:134px;left:570px;color: gray;font-size: 13px;font-weight: lighter;">{{fecha_($reserva[0]->fecha_evento,3)}}</h6>
<!-- end fecha factura -->

<!-- Direccion y ciudad -->
<h6 style="position: absolute;top:160px;left:125px;color: gray;font-size: 13px;font-weight: lighter;">{{cliente($reserva[0]->cliente,7)}}</h6>    
<h6 style="position: absolute;top:160px;left:420px;color: gray;font-size: 13px;font-weight: lighter;">{{validarbodega($reserva[0]->bodega)}}</h6>
<!-- end direccion ciudad -->

<div style="position: absolute;top:255px;left:15px;color: gray;width:95%">
<table width="100%" style="font-size: 12px">
<thead>
</thead>
  <tbody >
  {{pro($reserva[0]->cantidad,$reserva[0]->producto,$reserva[0]->cliente)}}    
  </tbody>
</table>
</div>

<div style="position: absolute;top:600px;left:15px;color: gray;width:95%">
<table width="100%" style="font-size: 12px">
<thead>
</thead>
  <tbody >
  {{recargos($factura[0]->concepto_impuestos,$factura[0]->impuestos)}}    
  
  </tbody>
</table>

</div>

@php
 $total=$factura[0]->total_facturado+array_sum(explode(',',$factura[0]->impuestos))-array_sum(explode(',',$factura[0]->descuentos))
 @endphp

<div style="position: absolute;top:673px;left:48px;color: gray;width:53%">

<h5>{{convertir($total*1.19)}}</h5>

</div>

<div style="position: absolute;top:670px;left:530px;color: gray;width:20%;text-align: center;">

<h5>{{formatoprecio($factura[0]->total_facturado+array_sum(explode(',',$factura[0]->impuestos)))}}</h5>

</div>

<div style="position: absolute;top:688px;left:530px;color: gray;width:20%;text-align: center;">

<h5>{{formatoprecio(array_sum(explode(',',$factura[0]->descuentos)))}}</h5>

</div>

<div style="position: absolute;top:704px;left:530px;color: gray;width:20%;text-align: center;">

<h5>$ 0</h5>

</div>

<div style="position: absolute;top:721px;left:530px;color: gray;width:20%;text-align: center;">

<h5>$ 0</h5>

</div>  

<div style="position: absolute;top:738px;left:530px;color: gray;width:20%;text-align: center;">

<h5>$ 0</h5>

</div>

<div style="position: absolute;top:756px;left:530px;color: gray;width:20%;text-align: center;">

<h5>{{formatoprecio($total*0.19)}}</h5>

</div>

<div style="position: absolute;top:778px;left:530px;color: gray;width:20%;text-align: center;">

<h5>{{formatoprecio($total*1.19)}}</h5>

</div>



</div>
   


   
</body>
</html>
