<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use Auth;

class ViewController extends Controller
{
  
  public function entradaproducto()
  {
      validaradministrador(2);
    $items=DB::table('app_listas')->where('id_tipo_lista',1)->select('valor_lista','valor_item')->get();
    $bodegas=DB::table('app_bodegas')->select('id','nombre')->get();
    $productos=DB::table('app_productos')->orderby('nombre','asc')->select('nombre','codigo','descripcion','vl_unitario','vl_mayorista','vl_minorista')->get();
    return View::make("register.entradaproducto")->with(array('productos'=>$productos,'items'=>$items,'bodegas'=>$bodegas));
  }
  public function salidaproducto()
  {
      validaradministrador(2);

    $productos=DB::table('app_productos')->get();
    $items=DB::table('app_listas')->where('id_tipo_lista',1)->get();
    return View::make("register.salidaproducto")->with(array('productos'=>$productos,'items'=>$items));
  }

  public function confirmar1()
  {
    $producto=$_GET['productid'];
    $cantidad=$_GET['cantidad'];
    $bodega=$_GET['bodega'];
    $tiempo=$_GET['tiempo'];
     $tiempo=$_GET['tiempo'];
     $tiempoa=explode('-', $tiempo);
     $fechaini=fechaconfirmar($tiempoa[0]);
     $fechafin=fechaconfirmar($tiempoa[1]);


    $ini=0;
    $total=DB::table('app_movimientos')->where('producto_id', $producto)->where('bodega',$bodega )->take(1)->orderby('creado','desc')->select('total')->get();
    $codigosf=$producto;
    $productosfinal=DB::table('app_productos')->where('codigo', $producto)->take(1)->value('nombre');

    foreach ($total as $value) {
      $ini=$value->total;
    }

    $totales=$ini;
    $cantidadreservada=0;
    

  
$disponible=disponibles($bodega,$fechaini,$fechafin);


    foreach ($disponible as $value) {
      $productosre=$value->producto;
      $coincidencia = strpos($productosre, $producto);
      if (!($coincidencia === false)) {
        $produc=explode(",", $value->producto);
        $cant=explode(",", $value->cantidad);
        $position=array_search($producto, $produc);
        $cantidadreservada=$cantidadreservada+$cant[$position];
     }
    }
    $disponibles=$ini-$cantidadreservada;
    if ($cantidad>$disponibles) {
    echo "<p style='color:red'>".$disponibles ." disponibles para alquiler en las fechas seleccionadas<p>";
    }else {
    $disp=$totales-$cantidadreservada;
    echo $cantidadreservada." productos reservados de ".$totales." , disponibles ". $disp;
     }
  }


public function movimientos()
{
  if (Auth::user()->id==1) {
  $movimientos=DB::table('app_movimientos')->groupBy('id_m')->get();
  return View::make("show.movimientos")->with(array('movimientos'=>$movimientos));
  }else{
     $movimientos=DB::table('app_movimientos')->where('bodega',Auth::user()->bodega_id)->groupBy('id_m')->get();
  return View::make("show.movimientos")->with(array('movimientos'=>$movimientos));
  }
}
public function direccioncliente()
{
$id=$_GET['id'];
$cliente=DB::table('app_clientes')->select('direccion')->where('id',$id)->take(1)->get();
foreach ($cliente as $value) {
  $direccion=$value->direccion;
}
echo "<label style='color:black'>".$direccion."</label>";
}
public function movimientoid()
{
$id=$_GET['id'];
$movimientos=DB::table('app_movimientos')->where('id_m',$id)->get();
foreach ($movimientos as $value) {
  $descripcion=$value->descripcion;
  break;
}
return View('show.movimiento')->with(array('movimientos'=>$movimientos,'descripcion'=>$descripcion));
}




}
