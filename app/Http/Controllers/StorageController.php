<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use DB;
use Auth;
use \Milon\Barcode\DNS1D;
use File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Storage;
use Carbon\Carbon;
use View;
class StorageController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {    $this->middleware('auth');}
public function editarcliente(Request $request)
{
    validaradministrador(2);

  DB::table('app_clientes')->where('id', $request->id)->update([
    'nombre'=>strtoupper($request->nombre),'apellido'=>strtoupper($request->apellido),
    'telefono'=>$request->telefono,'celular'=>$request->celular,
    'correo'=>$request->correo,'direccion'=>$request->direccion,'tipo'=>$request->tipo
  ]);
  Session::flash('flash_message', 'Se ha modificado el cliente: ' .strtoupper($request->nombre)." ".strtoupper($request->apellido));
  return back();
}
public function registromovimiento(Request $request)
{
    validaradministrador(2);
 
  $todo=$request->all();
  $productos=$request->producto;
  $cantidades=$request->cantidad;
  $id_m=DB::table('app_movimientos')->max('id_m');
  $id_m=$id_m+1;
  for ($i=0; $i < count($productos); $i++) {

  if (Auth::user()->id_perfil==1) {
    if ($request->item==1 or $request->item==2) {
      if ($request->bodega_item==0) {
        Session::flash('error_message', 'Debe seleccionar una bodega');
        return back();
      }
        $bodega=$request->bodega_item;
  }
  }else {
    $bodega=Auth::user()->bodega_id;
  }
  $this->validate($request, [
    'producto' => 'required',
    'item' => 'required',
    'descripcion' => 'required',
    'cantidad' => 'required|min:1',
    ]);
    $totales="";
  if ($request->item==1) {
    $total=DB::table('app_movimientos')->where('producto_id', $productos[$i])->where('bodega',$bodega)->take(1)->orderby('creado','desc')->get();
    foreach ($total as $value) {
      $totales=$value->total;
    }
    if ($totales=="") {
      $totales=0;
    }
    DB::table('app_movimientos')->insert([['id_m'=>$id_m,'producto_id'=>$productos[$i],'item'=>$request->item,
    'descripcion'=>$request->descripcion,'cantidad'=>$cantidades[$i],'bodega'=>$bodega,'total'=>$totales+$cantidades[$i],
    'bodega_entrada'=>0,'creado'=>Carbon::now()]]);
  }elseif ($request->item==2) {
    for ($j=0; $j < count($productos); $j++) {

          $total=DB::table('app_movimientos')->where('producto_id', $productos[$j])->where('bodega',$bodega)->take(1)->orderby('creado','desc')->get();
          foreach ($total as $value) {
            $totales=$value->total;
          }
          if ($totales=="") {
            $totales=0;
          }

          if ($cantidades[$j]>$totales) {
            Session::flash('error_message', 'El producto '.validarproductoporcodigo($productos[$j])." solo tiene ".$totales." unidades disponibles para salida");
            return back();
          }
    }


      DB::table('app_movimientos')->insert([['id_m'=>$id_m,'producto_id'=>strtoupper($productos[$i]),'item'=>strtoupper($request->item),
      'descripcion'=>$request->descripcion,'cantidad'=>$cantidades[$i],'bodega'=>$bodega,'total'=>$totales-$cantidades[$i],
      'bodega_entrada'=>0,'creado'=>Carbon::now()]]);

  }elseif ($request->item==3) {
  if ($request->bodega_salida==0 or $request->bodega_entrada==0) {
    Session::flash('error_message', 'Debe seleccionar una bodega en cada campo');
    return back();
  }
  if ($request->bodega_salida==$request->bodega_entrada) {
    Session::flash('error_message', 'La bodega de destino debe ser diferente a la de salida');
    return back();
  }

  for ($j=0; $j < count($productos); $j++) {

        $totales=DB::table('app_movimientos')->where('producto_id', $productos[$j])->where('bodega',$request->bodega_salida)->take(1)->orderby('creado','desc')->value('total');
        if (empty($totales)) {
          $totales=0;
        }

        if ($cantidades[$j]>$totales) {
          Session::flash('error_message', 'El producto '.validarproductoporcodigo($productos[$j])." solo tiene ".$totales." unidades disponibles para traslado");
          return back();
        }
  }
    DB::table('app_movimientos')->insert([['id_m'=>$id_m,'producto_id'=>$productos[$i],'item'=>$request->item,
    'descripcion'=>$request->descripcion,'cantidad'=>$cantidades[$i],'bodega'=>$request->bodega_salida,'bodega_entrada'=>$request->bodega_entrada,
    'total'=>$totales-$cantidades[$i],'creado'=>Carbon::now()]]);

    DB::table('app_movimientos')->insert([['id_m'=>$id_m,'producto_id'=>$productos[$i],'item'=>2,
    'descripcion'=>'salen productos por traslado','cantidad'=>$cantidades[$i],'bodega'=>$request->bodega_salida,'total'=>$totales-$cantidades[$i],
    'bodega_entrada'=>0,'creado'=>Carbon::now()]]);
    $totald=DB::table('app_movimientos')->where('producto_id',$productos[$i] )->where('bodega',$request->bodega_entrada)->take(1)->orderby('creado','desc')->value('total');

    if (empty($totald)) {
      $total=0;
    }
    DB::table('app_movimientos')->insert([['id_m'=>$id_m,'producto_id'=>$productos[$i],'item'=>1,
    'descripcion'=>'entran productos por traslado','cantidad'=>$cantidades[$i],'bodega'=>$request->bodega_entrada,'total'=>$total+$cantidades[$i],
    'bodega_entrada'=>0,'creado'=>Carbon::now()]]);
  }
}

    $descripcion='idmovimiento = '.$id_m.', item = '.$request->item.', descripcion = '.$request->descripcion;

    DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>5,'caso'=>1,'identificador'=>$id_m,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);

Session::flash('flash_message', 'se ha registrado un movimiento: ');
return back();


}

}
