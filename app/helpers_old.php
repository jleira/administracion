<?php
use Carbon\Carbon;

function active($seccion){
  $url= $_SERVER["REQUEST_URI"];
    $pos = strpos($url, $seccion);
    if ($pos !== false) {
      return 'active';
} 

}
function max_categoria(){
  return $categoria=DB::table('app_listas')->where('id_tipo_lista',4)->max('valor_lista')+1;
}

function pass($pass){
  $p=bcrypt($pass);
  return $p;
}

function valordecompra($codigo){
return $producto=DB::table('app_productos')->where('codigo',$codigo)->value('vl_unitario');

}

function https(){
  if (isset($_SERVER['HTTPS'])) {

} else {
   $servidor=$_SERVER['HTTP_HOST'];
    header('Location: https://'.$servidor.$_SERVER['REQUEST_URI']);
    exit;
}
}
function http(){
  if (isset($_SERVER['HTTPS'])) {
   $servidor=$_SERVER['HTTP_HOST'];
    header('Location: http://'.$servidor.$_SERVER['REQUEST_URI']);
    exit;
}
}

function validarcertificado(){
  if (isset($_SERVER['HTTPS'])) {
    return true;
} else {
    return false;
}

}
function quitarespacio($ubicacion)
{
$ur="https://www.google.com.co/maps/@". str_replace(" ", "", $ubicacion).",17z";
 return $ur;
}
function validaradministrador($caso)
{

  if (Auth::guest()) {
    $servidor=$_SERVER['HTTP_HOST'];
    header('Location: http://'.$servidor);
    exit;
  }else {
    $perfil=Auth::user()->id_perfil;
  if ($caso==1) {
    if ($perfil==2) {
      $servidor=$_SERVER['HTTP_HOST'];
   header('Location: http://'.$servidor);
      exit;
    }
  }
 }
}

function fechajs($fecha){
  $dt = Carbon::parse($fecha);
  $ano=$dt->year;
  $mes=$dt->month;
  $dia=$dt->day;
  $horas=$dt->hour;
  $minutos=$dt->minute;
  return $mes."/".$dia."/".$ano." ".$horas.":".$minutos;
}
function fechajs2($fecha){
  $dt = Carbon::parse($fecha);
  $ano=$dt->year;
  $mes=$dt->month;
  if (strlen($mes)==1) {
    $mes="0".$mes;
  }
  $dia=$dt->day;
  if (strlen($dia)==1) {
    $dia="0".$dia;
  }
  $horas=$dt->hour;
  if (strlen($horas)==1) {
  $horas="0".$horas;
}
  $minutos=$dt->minute;
  if (strlen($minutos)==1) {
  $minutos="0".$minutos;
}
if ($horas>11) {
  $prefijo="PM";
}else{
  $prefijo="AM";
}
if ($horas>12) {
  $horas=$horas-12;
   $horas="0".$horas;
}


  return $dia."/".$mes."/".$ano." ".$horas.":".$minutos." ".$prefijo;
}

function validarproducto($id){
  if (empty($id)) {
return "";
  }else {
$producto=DB::table('app_productos')->select('nombre')->where('id',$id)->take(1)->get();
foreach ($producto as $product) {
  $producto=$product->nombre;
}
return $producto;
}
}
function validarproductoporcodigo($id){
  if (empty($id)) {
return "";
  }else {
$producto=DB::table('app_productos')->select('nombre')->where('codigo',$id)->take(1)->get();
foreach ($producto as $product) {
  $producto=$product->nombre;
}
return $producto;
}
}

function productos($codigos, $cantidades,$cliente)
{
  $codigos=explode(',',$codigos);
  $cantidades=explode(',',$cantidades);
  $contador=count($codigos);
  $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
  $contadorcategorias=DB::table('app_listas')->where('id_tipo_lista',4)->count();
  foreach ($categorias as $categoria) {
    $cat=0;
      for ($i=0; $i < $contador ; $i++) {
        if (buscarcategoria($codigos[$i],$categoria->valor_lista)) {
          if ($cat==0) {
            echo "<thead >
            <tr>
            <th colspan='5' style='text-align:center;padding:10px;border-radius: 0px;FONT-SIZE:20px'>".$categoria->valor_item."</th>

            </tr>".
              "
              <tr>
              <th style='text-align: center'>Codigo</th>
              <th style='text-align: center'>Producto</th>
              <th style='text-align: center'>Cantidad</th>
              <th style='text-align: center'>".precioporcliente($cliente,1)."</th>
              <th style='text-align: center'>Total</th>
              </tr>
              </thead>";
          }
          $cat=1;
          $prod=validarproductoporcodigo($codigos[$i]);
          echo "<tr><td>".$codigos[$i].'</td>'.
          "<td>".$prod.'</td>'.
          "<td>".$cantidades[$i].'</td>'.
          "<td> $ ".number_format(validarprecio($codigos[$i],$cliente)).'</td>'.
          "<td> $ ".number_format($cantidades[$i]*validarprecio($codigos[$i],$cliente)).'</td>'.
          '</tr>'
          ;
        }
      }
  }
}
function validarprecio($codigo,$cliente)
{
$caso=cliente($cliente,4);
if ($caso==1) {
$item="vl_minorista";
}elseif ($caso==2) {
$item="vl_mayorista";
}

$producto=DB::table('app_productos')->where('codigo',$codigo)->select($item)->take(1)->get();
foreach ($producto as $key) {
  return $key->$item;
}
}

function buscarcategoria($codigo,$categoria)
{
$producto=DB::table('app_productos')->where('codigo',$codigo)->where('categoria',$categoria)->count();
if ($producto==0) {
  return false;
}else {
  return true;
}
}



function validarlista($id,$lista){
  if (empty($id)) {
return "";
  }else {
$producto=DB::table('app_listas')->select('valor_item')->where('id_tipo_lista',$lista)->where('valor_lista',$id)->take(1)->get();
foreach ($producto as $product) {
  $producto=$product->valor_item;
}
return $producto;
}
}
function buscarfecha($tiempo)
{
  $año = substr($tiempo, 6, 4);
  $mes = substr($tiempo, 0, 2);
  $dia = substr($tiempo, 3, 2);
  return $año."-".$mes."-".$dia;
}
function buscarfechainventario($tiempo)
{
  $año = substr($tiempo, 6, 4);
  $mes = substr($tiempo, 3, 2);
  $dia = substr($tiempo, 0, 2);
  return $año."-".$mes."-".$dia;
}

function buscarfecha3($tiempo)
{
  $año = substr($tiempo, 6, 4);
  $mes = substr($tiempo, 3, 2);
  $dia = substr($tiempo, 0, 2);
  $horas=substr($tiempo,11, 5);
  $a=substr($tiempo,17, 2);
  $hora=substr($tiempo,11, 2);
  //return $hora;
  if ($a=='AM') {
    if ($hora==12) {
    $horas='00:'.substr($tiempo,13, 2).':00';
    }else {
      $horas=$horas.":00";
    }

  }else {
  if ($hora==12) {
  $horas=$horas.":00";
  }else {
    $hora=$hora+12;
    $horas=$hora.":".substr($tiempo,14, 2).":00";
  }
  }
  return $año."-".$mes."-".$dia." ".$horas;
}
function buscarfecha4($tiempo)
{
  $año = substr($tiempo, 6, 4);
  $mes = substr($tiempo, 3, 2);
  $dia = substr($tiempo, 0, 2);
  $horas=substr($tiempo,11, 5);
  $a=substr($tiempo,17, 2);
  $hora=substr($tiempo,11, 2);
  //return $hora;
  if ($a=='AM') {
    if ($hora==12) {
    $horas='00:'.substr($tiempo,13, 2).':00';
    }else {
      $horas=$horas.":00";
    }

  }else {
  if ($hora==12) {
  $horas=$horas.":00";
  }else {
    $hora=$hora+12;
    $horas=$hora.":".substr($tiempo,13, 2).":00";
  }
  }
  return $año."-".$mes."-".$dia." ".$horas;
}
function buscarfecha5($tiempo)
{
  $ano = substr($tiempo, 6, 4);
  $dia = substr($tiempo, 3, 2);
  $mes = substr($tiempo, 0, 2);
  $horas=substr($tiempo,11, 5);
  $t=explode(":", $horas);
  $min=str_replace(" ", "", $t[1]).":00";
  $pos = strpos($tiempo, 'AM');
  if ($pos !== false) {
if (strlen($t[0])==1) {
  $t[0]="0".$t[0];
}
if ($t[0]==12) {
  $t[0]="00";
}
  }else{
    if ($t[0]==12) {

}else{
 $t[0]=$t[0]+12;
}
  }
  return $ano."-".$mes."-".$dia." ".$t[0].":".$min;;
}

function activo($credito)
{
if ($credito==1) {
return "SI";
}else {
  return "NO";
}
}
function buscarfecha2($tiempo)
{
  $año = substr($tiempo, 6, 4);
  $mes = substr($tiempo, 3, 2);
  $dia = substr($tiempo, 0, 2);
  $horas=substr($tiempo,10, 5);
  $a=substr($tiempo,15, 2);
  $hora=substr($tiempo,10, 2);

  if ($a=='AM') {
    if ($hora==12) {
    $horas='00:'.substr($tiempo,13, 2).':00';
    }else {
      $horas=$horas.":00";
    }

  }else {
  if ($hora==12) {
  $horas=$horas.":00";
  }else {
    $hora=$hora+12;
    $horas=$hora.":".substr($tiempo,13, 2).":00";
  }
  }
  return $año."-".$mes."-".$dia." ".$horas;
}

function validarbodega($id)
{
  if (empty($id)) {
return"";
  }else {
$producto=DB::table('app_bodegas')->select('nombre')->where('id',$id)->take(1)->get();
foreach ($producto as $product) {
  $producto=$product->nombre;
}
return $producto;
}
}
function eliminarimagenes($link,$codigo)
{
if (empty($link)) {
echo "<label>no hay imagenes para mostrar</label>";
}else {

echo '<div class="col-lg-6">';
$links = explode(",", $link);
for ($i=0; $i <count($links) ; $i++) {
echo '<div class="col-lg-6 thumb" style="position:relative">
      <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="" data-caption="" data-image="'.rutaimagenes().$codigo.'/'.$links[$i].'" data-target="#image-gallery">';
echo '<img src="'.rutaimagenes().$codigo.'/'.$links[$i].'" alt="" class=" img-responsive" width="100%">
<a href="javascript:eliminarimagen(';
echo  "'".$codigo."/".$links[$i]."')";
echo '" style="position:absolute;top: -8px;right: 5%;color: red;"><i class="fa fa-trash" aria-hidden="true"></i></a>';
echo "</a></div>";
}
echo '</div>';
echo '<div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">
                <img id="image-gallery-image" class="img-responsive" src="">
            </div>
            <div class="modal-footer">
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="show-previous-image">Anterior</button>
                </div>

                <div class="col-md-8 text-justify" id="image-gallery-caption">
                    This text will be overwritten by jQuery
                </div>

                <div class="col-md-2">
                    <button type="button" id="show-next-image" class="btn btn-default">Siguiente</button>
                </div>
            </div>
        </div>
    </div>
</div>';


}
}
function buscarimagenes($link,$codigo)
{
if (empty($link)) {
echo "<label>no hay imagenes para mostrar</label>";
}else {

echo '<div class="col-lg-6">';
$links = explode(",", $link);
for ($i=0; $i <count($links) ; $i++) {
echo '<div class="col-lg-6 thumb" style="position:relative">
      <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="" data-caption="" data-image="'.rutaimagenes().'/'.$codigo.'/'.$links[$i].'" data-target="#image-gallery">';
echo '<img src="'.rutaimagenes().'/'.$codigo.'/'.$links[$i].'" alt="" class=" img-responsive" width="100%">';
echo "</a></div>";
}
echo '</div>';
echo '<div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">
                <img id="image-gallery-image" class="img-responsive" src="">
            </div>
            <div class="modal-footer">
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="show-previous-image">Anterior</button>
                </div>

                <div class="col-md-8 text-justify" id="image-gallery-caption">
                    This text will be overwritten by jQuery
                </div>

                <div class="col-md-2">
                    <button type="button" id="show-next-image" class="btn btn-default">Siguiente</button>
                </div>
            </div>
        </div>
    </div>
</div>';


}
}


function rutaimagenes()
{
   $host=$_SERVER['HTTP_HOST'];
  return "http://".$host."/storage/app/public/";
   //return "http://".$host."/net-administrativo/storage/app/public/";
}

function cliente($id,$caso)
{
  //caso=1->nombre del cliente
  //caso=2->correo del cliente
  //caso=3->telefono del cliente
if ($caso==1) {
$item=['nombre','apellido'];
}elseif ($caso==2) {
$item=["correo"];
}elseif ($caso==3) {
$item=["telefono"];
}elseif ($caso==4) {
$item=["tipo"];
}elseif ($caso==5) {
$item=["celular"];
}
$valor="";
$cliente=DB::table('app_clientes')->select($item)->where('id',$id)->take(1)->get();
foreach ($cliente as $cliente) {
  foreach ($item as $key) {
    $valor=$valor." ".$cliente->$key;
  }
}
return $valor;
}
function buscarproductos($codigos,$cantidades,$cliente)
{

  $codigos=explode(',',$codigos);
  $cantidades=explode(',',$cantidades);
  $contador=count($codigos);
  $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
  $contadorcategorias=DB::table('app_listas')->where('id_tipo_lista',4)->count();
  foreach ($categorias as $categoria) {
    $totalcategoria=0;
    $cat=0;
      for ($i=0; $i < $contador ; $i++) {
        if (buscarcategoria($codigos[$i],$categoria->valor_lista)) {
          if ($cat==0) {
            echo '<table style="width:100%;margin: 10px 0px 10px 0px;">
              <thead style="background-color:#792929;color:white">
                <tr class="center-block">
                  <th colspan="5" style="text-align:center;padding:4px;border-radius: 0px;FONT-SIZE: 15px;" >'.$categoria->valor_item.'</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th style="text-align:center">CANTIDAD</th>
                  <th style="text-align:center">CODIGO</th>
                  <th style="text-align:center">PRODUCTO</th>
                  <th style="text-align:center">PRECIO</th>
                  <th style="text-align:center">TOTAL</th>
                </tr>';
          }
          $cat=1;
          $prod=validarproductoporcodigo($codigos[$i]);
          echo "<tr><td style='text-align:center;FONT-SIZE: 10px'>".$cantidades[$i].'</td>'.
          "<td style='text-align:center;FONT-SIZE: 12px'>".$codigos[$i].'</td>'.
          "<td style='text-align:center;FONT-SIZE: 12px'>".$prod.'</td>'.
          "<td style='text-align:center;FONT-SIZE: 12px'>$ ".number_format(validarprecio($codigos[$i],$cliente)).'</td>'.
          "<td style='text-align:center;FONT-SIZE: 12px'>$ ".number_format($cantidades[$i]*validarprecio($codigos[$i],$cliente)).'</td>'.
          '</tr>';
          $totalcategoria=$totalcategoria+($cantidades[$i]*validarprecio($codigos[$i],$cliente));
        }
      }
      echo "</tbody>
            </table>";
  }

}
function buscarproductosexcel($codigos,$cantidades,$cliente)
{
  $codigos=explode(',',$codigos);
  $cantidades=explode(',',$cantidades);
  $contador=count($codigos);
  $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
  foreach ($categorias as $categoria) {
    $totalcategoria=0;
    $cat=0;
      for ($i=0; $i < $contador ; $i++) {
        if (buscarcategoria($codigos[$i],$categoria->valor_lista)) {
          if ($cat==0) {
            echo '<tr>
 <td colspan="5"></td>
</tr>
                <tr>
                  <td colspan="5" style="font-size: 15px;text-align:center;background-color:#792929 ;color: #fffff !important" >'.$categoria->valor_item.'</td>
                </tr>
                <tr>
                  <td  style="text-align:center">CANTIDAD</td>
                  <td  style="text-align:center">CODIGO</td>
                  <td  style="text-align:center">PRODUCTO</td>
                  <td  style="text-align:center">PRECIO</td>
                  <td  style="text-align:center">TOTAL</td>
                </tr>';
          }
          $cat=1;
          $prod=validarproductoporcodigo($codigos[$i]);
          echo "<tr><td style='text-align:center;FONT-SIZE: 12px'>".$cantidades[$i].'</td>'.
          "<td style='text-align:center;FONT-SIZE: 12px'>".$codigos[$i].'</td>'.
          "<td style='text-align:center;FONT-SIZE: 12px'>".$prod.'</td>'.
          "<td style='text-align:center;FONT-SIZE: 12px'>$ ".number_format(validarprecio($codigos[$i],$cliente)).'</td>'.
          "<td style='text-align:center;FONT-SIZE: 12px'>$ ".number_format($cantidades[$i]*validarprecio($codigos[$i],$cliente)).'</td>'.
          '</tr>';
          $totalcategoria=$totalcategoria+($cantidades[$i]*validarprecio($codigos[$i],$cliente));
        }
      }
  }

}
function formatofecha($fecha)
{
  $fecha=Carbon::parse($fecha);
  $fecha=$fecha->format('l jS  F  h:i:s A');
  $fecha=str_replace("st","",$fecha);
  $fecha=str_replace("th","",$fecha);
  $fecha=str_replace("rd  ","",$fecha);
  $fecha=cambiardia($fecha);
  $fecha=Cambiarmes($fecha);
  return $fecha;
}
function cambiardia($fecha)
{
  $fecha=str_replace("Monday","Lunes",$fecha);
  $fecha=str_replace("Tuesday","Martes",$fecha);
  $fecha=str_replace("Wednesday","Miercoles",$fecha);
  $fecha=str_replace("Thursday","Jueves",$fecha);
  $fecha=str_replace("Friday","Viernes",$fecha);
  $fecha=str_replace("Saturday","Sabado",$fecha);
  $fecha=str_replace("Sunday","Domingo",$fecha);
  return $fecha;
}
function cambiarmes($fecha)
{
  $fecha=str_replace("January","Enero",$fecha);
  $fecha=str_replace("February","Febrero",$fecha);
  $fecha=str_replace("March","Marzo",$fecha);
  $fecha=str_replace("April","Abril",$fecha);
  $fecha=str_replace("May","Mayo",$fecha);
  $fecha=str_replace("June","Junio",$fecha);
  $fecha=str_replace("July","Julio",$fecha);
  $fecha=str_replace("August","Agosto",$fecha);
  $fecha=str_replace("September","Septiembre",$fecha);
  $fecha=str_replace("October","Octubre",$fecha);
  $fecha=str_replace("November","Noviembre",$fecha);
  $fecha=str_replace("December","Diciembre",$fecha);
  return $fecha;
}
function precioporcliente($cliente,$caso)
{
  //caso=1 mostramos vl mayorista o minorista
  if ($caso==1) {
    $show=cliente($cliente,4);
    if ($show==1) {
      return "V. MINORISTA";
    }else {
return "V. MAYORISTA";
  }
  }
}
  function resumen($codigos,$cantidades,$cliente,$concepto_descuento,$descuento,$concepto_impuesto,$impuesto,$iva)
{
  $codigos=explode(',',$codigos);
  $cantidades=explode(',',$cantidades);
  $contador=count($codigos);
  $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
  $contadorcategorias=DB::table('app_listas')->where('id_tipo_lista',4)->count();
$totalsuma=0;
  foreach ($categorias as $categoria) {
    $cat=0;
    $total=0;
    $cantidadporcategoria=0;

      for ($i=0; $i < $contador ; $i++) {
        if (buscarcategoria($codigos[$i],$categoria->valor_lista)) {

          $precio=$cantidades[$i]*validarprecio($codigos[$i],$cliente);
          $cantidadporcategoria=$cantidadporcategoria+$cantidades[$i];
          $total=$total+$precio;

          if ($cat==0) {
            $cat=1;
          }
        }
      }
      if ($cat==1) {
        echo "<tr><td style='text-align:center'>".$cantidadporcategoria. "</td>";
        echo "<td style='text-align:left'>".$categoria->valor_item. "</td>";
        echo "<td style='text-align:right'>$ ".number_format($total)."</td></tr>";
        $totalsuma=$totalsuma+$total;
      }
  }
  echo "  <tr><td></td><th style='text-align:left'>Neto :</th><td style='text-align:right'>$ ".number_format($totalsuma)."</td></tr>";


  $conceptosdescuento=explode(',',$concepto_descuento);
  $descuentos=explode(',',$descuento);
  $contadordescuento=count($conceptosdescuento);
  $totaldescuentos=0;
  for ($i=0; $i < $contadordescuento ; $i++) {
    if (empty($conceptosdescuento[$i])) {
    }else{
        echo "<tr style='border-style: hidden'><td></td><th style='text-align:left'>".strtoupper($conceptosdescuento[$i]). " :</th>";
        echo "<td style='text-align:right'>- $ ".number_format($descuentos[$i])."</td></tr>";
        $totaldescuentos=$totaldescuentos+$descuentos[$i];
    }

    }

    $conceptosimpuesto=explode(',',$concepto_impuesto);
    $impuesto=explode(',',$impuesto);
    $contadorimpuestos=count($conceptosimpuesto);
    $totalimpuesto=0;
    for ($i=0; $i < $contadorimpuestos ; $i++) {
      if (empty($conceptosimpuesto[$i])) {
      }else{
        echo "<tr style='border-style: hidden'><td></td><th style='text-align:left'>".strtoupper($conceptosimpuesto[$i]). " :</th>";
          echo "<td style='text-align:right' > $ ".number_format($impuesto[$i])."</td></tr>";
        $totalimpuesto= $totalimpuesto+$impuesto[$i];
      }

      }
       $final=$totalsuma-$totaldescuentos+$totalimpuesto;
        echo "
  <tr><td></td><th style='text-align:left'>SUBTOTAL :</th><td style='text-align:right'>$ ".number_format($final)."</td></tr>";
               if ($iva==0) {
    echo "<tr><th colspan='3' style='color:white'>.</th></tr>
  <tr><td></td><th style='text-align:left'>IVA :</th><td style='text-align:right'>$ 0</td></tr>";
  }else{
    (float)$cobrariva=$final*0.19;
    (float)$totalsuma=$final+$cobrariva;
    echo "<tr><th colspan='3' style='color:white'>.</th></tr>
  <tr><td></td><th style='text-align:left'>IVA :</th><td style='text-align:right'>$ ".number_format($cobrariva)."</td></tr>";
  }
  
        echo "<tr style='border-style: hidden'><td></td><th style='text-align:left'>TOTAL :</th><td style='text-align:right'>$ ".number_format($totalsuma)."</td></tr>";
}


  function resumenexcel($codigos,$cantidades,$cliente,$concepto_descuento,$descuento,$concepto_impuesto,$impuesto,$iva)
{

  $codigos=explode(',',$codigos);
  $cantidades=explode(',',$cantidades);
  $contador=count($codigos);
  $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
  $contadorcategorias=DB::table('app_listas')->where('id_tipo_lista',4)->count();
$totalsuma=0;
  foreach ($categorias as $categoria) {
    $cat=0;
    $total=0;
    $cantidadporcategoria=0;

      for ($i=0; $i < $contador ; $i++) {
        if (buscarcategoria($codigos[$i],$categoria->valor_lista)) {

          $precio=$cantidades[$i]*validarprecio($codigos[$i],$cliente);
          $cantidadporcategoria=$cantidadporcategoria+$cantidades[$i];
          $total=$total+$precio;
          echo "<tr><td colspan='1' style='text-align:center'>".$cantidadporcategoria. "</td>";

          if ($cat==0) {
            echo "<td colspan='2' style='text-align:left'>".$categoria->valor_item. "</td>";
            $cat=1;
          }
        }
      }
      if ($cat==1) {
        echo "<td colspan='2' style='text-align:right'>$ ".number_format($total)."</td></tr>";
        $totalsuma=$totalsuma+$total;
      }
  }
  echo "
  <tr><td></td><td colspan='2' style='text-align:left'>Neto </td><td colspan='2' style='text-align:right'>$ ".number_format($totalsuma)."</td></tr><tr><td></td><td colspan='2'></td><td colspan='2'></td></tr>";

  $conceptosdescuento=explode(',',$concepto_descuento);
  $descuentos=explode(',',$descuento);
  $contadordescuento=count($conceptosdescuento);
  $totaldescuentos=0;
  if ($iva==1) {
    echo "<tr><td></td><td colspan='2' style='text-align:left'>IVA </td><td colspan='2' style='text-align:right'>$ "
    .number_format(0.19*$totalsuma)."</td></tr>";
    $totalsuma=1.19*$totalsuma;
  }
  else{
    echo "<tr><td></td><td colspan='2' style='text-align:left'>IVA </td><td colspan='2' style='text-align:right'>$ 0</td></tr>";

  }
   echo "
  <tr><td></td><td colspan='2' style='text-align:left'>SUBTOTAL </td><td colspan='2' style='text-align:right'>$ ".number_format($totalsuma)."</td></tr>";
  for ($i=0; $i < $contadordescuento ; $i++) {
    if (empty($conceptosdescuento[$i])) {
      # code...
    }else{
        echo "<tr><td></td><td colspan='2' style='text-align:left'>".strtoupper($conceptosdescuento[$i]). " </td>";
        echo "<td colspan='2' style='text-align:right'>- $ ".number_format($descuentos[$i])."</td></tr>";
        $totaldescuentos=$totaldescuentos+$descuentos[$i];
    }

    }

    $conceptosimpuesto=explode(',',$concepto_impuesto);
    $impuesto=explode(',',$impuesto);
    $contadorimpuestos=count($conceptosimpuesto);
    $totalimpuesto=0;
    for ($i=0; $i < $contadorimpuestos ; $i++) {
      if (empty($conceptosimpuesto[$i])) {
      }else{
                  echo "<tr><td></td><td colspan='2' style='text-align:left'>".strtoupper($conceptosimpuesto[$i]). " </td>";
          echo "<td colspan='2' style='text-align:right' > $ ".number_format($impuesto[$i])."</td></tr>";
        $totalimpuesto= $totalimpuesto+$impuesto[$i];

      }
      }
       $final=$totalsuma-$totaldescuentos+$totalimpuesto;
        echo "<tr style='border-style: hidden'><td></td><td colspan='2' style='text-align:left'>TOTAL :</td><td colspan='2' style='text-align:right'>$ ".number_format($final)."</td></tr>";
}



function valorfactura($codigos,$cantidades,$cliente)
{
  if (empty($codigos)) {
return 0;
  }
  $codigos=explode(',',$codigos);
  $cantidades=explode(',',$cantidades);
  $contador=count($codigos);
  $total=0;
      for ($i=0; $i < $contador ; $i++) {
        if (empty($codigos[$i]) or empty($cantidades[$i])) {
          # code...
        }else{
          $total=$total+($cantidades[$i]*validarprecio($codigos[$i],$cliente));
        }
      }
      return $total;
}

function buscar_descuentos($conceptos,$descuentos)
{
$conceptos=explode(',',$conceptos);
$descuentos=explode(',',$descuentos);
$contador=count($conceptos);
for ($i=0; $i < $contador; $i++) {
  if (empty($conceptos[$i])) {
    # code...
  }else {
  echo "<tr>
  <td>".$conceptos[$i]."</td>
  <td style='color:red'>$ ".number_format($descuentos[$i])."</td>
  </tr>
";}
}
}

function buscar_recargos($conceptos,$descuentos)
{
$conceptos=explode(',',$conceptos);
$descuentos=explode(',',$descuentos);
$contador=count($conceptos);
for ($i=0; $i < $contador; $i++) {
  if (empty($conceptos[$i])) {

  }else{
  echo "<tr>
  <td>".$conceptos[$i]."</td>
  <td style='color:green'>$ ".number_format($descuentos[$i])."</td>
  </tr>
";
  }

}
}
function formatoprecio($precio)
{
  return "$ ". number_format($precio);
}
function colorprincipal()
{
  $color=DB::table('app_generales')->where('id',1)->select('color')->get();
foreach ($color as $color) {
  $colorp=$color->color;
}

  echo "
  <style>
  .fileinput-upload{
    display: none !important;
  }

  .fileinput-upload-button{
    display: none !important;
  }

  .navbar{
    background-color: $colorp !important;
  }

  .logo{
    background-color: $colorp !important;
  }

  .user-header{
    background-color: $colorp !important;
  }

  .skin-red .sidebar-menu > li:hover > a, .skin-red .sidebar-menu > li.active > a {
      color: #ffffff;
      background: #1e282c;
      border-left-color: $colorp !important;
  }
</style>";
return "";
}
function nombreempresa(){
$color=DB::table('app_generales')->where('id',1)->select('nombre')->get();
foreach ($color as $color) {
  $colorp=$color->nombre;
}
return $colorp;
}
function logo()
{
  $host=$_SERVER['HTTP_HOST'];
  $logo=DB::table('app_generales')->where('id',1)->select('logo')->get();
foreach ($logo as $logo) {
  $log=$logo->logo;
}
  return "http://".$host."/storage/app/public/".$log;
  //return "http://".$host."/net-administrativo/storage/app/public/".$log;

}
function productosedicion($orden,$productos,$cantidades)
{
  if (empty($productos)) {
    return "No hay Productos registrados para este pedido";
  }
$productos=explode(",",$productos);
$cantidades=explode(",",$cantidades);
$count=count($productos);

echo '<table class="table table-hover" id="productos">
    <thead>
      <tr>
        <th>Codigo</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Opciones</th>
      </tr>
    </thead>
    <tbody>';
    for ($i=0; $i < $count; $i++) {
      echo '<tr>
        <td>'.$productos[$i].'</td>
        <input type="hidden" id="codigoproducto'.$i.'" value="'.$productos[$i].'">
        <input type="hidden" id="cantidadproducto'.$i.'" value="'.$cantidades[$i].'">
        <td>'.validarproductoporcodigo($productos[$i]).'</td>
        <td><input value="'.$cantidades[$i].'" type="number" id="cantidadfinal'.$i.'" onchange="capturar2('.$i.')" style="border-radius: 10px 10px 10px 10px;padding: 2px;border-style: groove;vertical-align: top;text-align: center;width: 50px;" /></td>
        <td><a class="eliminarp" href="javascript:eliminarproducto(';
          echo  "'".$orden."/".$productos[$i]."'";
          echo ')"><i class="fa fa-trash"></i></a>
          <a class="disableda" id="aeditar'.$i.'" href="javascript:editarproducto(';
          echo  "'".$i."','".$productos[$i]."'";
          echo ')"><i class="fa fa-edit"></i></a></td>
      </tr>';
    }
  echo '</tbody>
  </table>

  <script type="text/javascript">
$(function () {
  $("#productos").DataTable({
    "language": {
          "lengthMenu": "Mostrar _MENU_ productos por pagina",
          "zeroRecords": "No hay productos registrados",
          "info": "Pagina _PAGE_ de _PAGES_",
          "infoEmpty": "Ninguna bodega encontrada",
          "infoFiltered": "(Filtrado de _MAX_ bodegas )",
          "search":         "Buscar:",
          "paginate": {
      "first":      "Primero",
      "last":       "Ultimo",
      "next":       "Siguiente",
      "previous":   "Anterior"
  }
      }
  });
});
</script>';

}
function enviarcorreo()
 {
    $mailin = new Mailin('https://api.sendinblue.com/v2.0',env('SENDINBLUE_KEY'));
    $data = array( "to" => array('jesusleira2011@gmail.com'=>"oclock eventos"),
      "from" => array(env('MAIL_USERNAME'),'Cotizacion de evento'),
      "replyto" => array('oclockeventos@gmail.com','oclockeventos@gmail.com'  ),
      "html" =>"<html><body>ESTO ES UNA PRUEBA</body></html>",
      "attachment" => array(),
      "headers" => array("Content-Type"=> "text/html; charset=iso-8859-1","X-param1"=> "value1", "X-param2"=> "value2","X-Mailin-custom"=>"my custom value", "X-Mailin-IP"=> "102.102.1.2", "X-Mailin-Tag" => "My tag")
  );
$mailin->send_email($data);
 }
 function pagos($reserva)
 {
$facturas=DB::table('app_facturaciones')->where('id_reserva',$reserva)->orderby('id','asc')->get();
  echo "<tr><th colspan='3' style='color:white'>.</th></tr>";
  echo "<tr><th style='color:white'></th><th style='color:#950248'>ABONOS</th><th style='color:white'></th></tr>";
  echo "<tr><th style='color:white'></th><th >Fecha</th><th class='pull-right' style='text-align:right' >Valor</th></tr>";

  foreach ($facturas as $value) {
$abonototal=$value->total_abonado;
$saldo=$value->total_restante;
if ($value->abono==0) {
}else{
 echo "<tr style='border-style: hidden'><td></td><th style='text-align:left'>".formatofecha($value->fecha_abono). " </th>";
  echo "<td style='text-align:right'> $ ".number_format($value->abono) ."</td></tr>";
}

}
  echo "<tr><th style='color:white'></th><th >TOTAL ABONADO</th><th class='pull-right' style='text-align:right'>$ ".number_format($abonototal)."</th></tr>";
  echo "<tr><th style='color:white'></th><th >SALDO</th><th class='pull-right' style='text-align:right;color:red'>$ ".number_format($saldo)."</th></tr>";


 }
 function dividirtexto($cadena, $separador){
  $newtext = wordwrap($cadena, $separador, "<br/>");
  echo $newtext;
  return ;
 }
