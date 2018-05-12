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



class ProductosController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }


  public function codigodisponible($categoria)//se obtiene el valor del codigo, y se manda a la vista de registrar
  {
    validaradministrador(2);
  $productos=DB::table('app_productos')->where('categoria',$categoria)->count();
    $producto=$productos+1;
    $count=strlen($producto);
    if ($count==1) {
      $producto="0".$producto;
    }
    $codigodisponible=DB::table('app_productos')->where('codigo',$categoria.$producto)->count();
    while ($codigodisponible>0) {
      $producto=$producto+1;
      $count=strlen($producto);
      if ($count==1) {
        $producto="0".$producto;
      }
      $codigodisponible=DB::table('app_productos')->where('codigo',$categoria.$producto)->count();
    }
    return $categoria.$producto;
  }
  public function registrarproducto()
  {
    https();
      validaradministrador(2);

  $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->select('valor_lista','valor_item')->get();
  $productos=DB::table('app_productos')->select('codigo','nombre','descripcion','vl_mayorista','vl_minorista')->get();
  return View::make("productos.registrarproducto")->with(array('productos'=>$productos,'categorias'=>$categorias));
  }
  public function crearproducto(Request $request)
  {
      validaradministrador(2);
    if ($request->has('editarcodigo')) {
        $this->validate($request, [
          'nombre' => 'required|unique:app_productos,nombre',
          'codigo' => 'required|unique:app_productos,codigo',
          'categoria' =>'required',
          'vrunitario'=>'required'
      ]);
      $codigo=$request->codigo;
    }else {
        $this->validate($request, [
          'nombre' => 'required|unique:app_productos,nombre',
          'Codigo' => 'required|unique:app_productos,codigo',
          'categoria' =>'required',
          'vrunitario'=>'required'

      ]);
      $codigo=$request->Codigo;
    }

    if ($request->hasfile('imagenes')) {
      $files=$request->imagenes;
      foreach ($files as $file) {
        $public = \Storage::disk('public');
        $imageFileName = mt_rand(1,2147) . '.' . $file->getClientOriginalExtension();
        $filePath = $imageFileName;
         $public->put('/'.(int)$codigo.'/'.$filePath, file_get_contents($file), 'public');
        $link_[]=$imageFileName;
      }
    }else {
      $link_[]="";
    }
    $linki=implode(",",$link_);

  DB::table('app_productos')->insert([['nombre'=>strtoupper($request->nombre),'codigo'=>$codigo,
  'descripcion'=>strtoupper($request->descripcion),'vl_mayorista'=>str_replace('.','',$request->vrmayorista),'vl_unitario'=>str_replace('.','',$request->vrunitario),'vl_minorista'=>str_replace('.','',$request->vrminorista),
  'imagen'=>$linki,'fecha_registro'=>Carbon::today(),'categoria'=>$request->categoria ]]);

  $descripcion='nombre = '.strtoupper($request->nombre).', codigo = '.$codigo.',descripcion = '.strtoupper($request->descripcion).', vl_mayorista = '.str_replace('.','',$request->vrmayorista).', vl_unitario = '.str_replace('.','',$request->vrunitario).', vl_minorista = '.str_replace('.','',$request->vrminorista).', imagen = '.$linki.', fecha_registro = '.Carbon::today().', categoria = '.$request->categoria ;

  $idf=DB::table('app_productos')->max('id');

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>4,'caso'=>1,'identificador'=>$idf,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);


  Session::flash('flash_message', 'se ha guardado el siguiente producto: ' .strtoupper($request->nombre));
  return redirect('producto/'.$codigo);
  }
  public function productos()
  {
      $productos=DB::table('app_productos')->select('codigo','nombre','descripcion','vl_unitario','vl_mayorista','vl_minorista','categoria')->get();
    return View::make("productos.buscarproducto")->with(array('productos'=>$productos));
  }

  public function editarvista()
  {
      validaradministrador(1);

    $productos=DB::table('app_productos')->select('codigo','nombre','vl_unitario','vl_mayorista','vl_minorista','categoria')->get();
    return View::make("productos.editar")->with(array('productos'=>$productos));
  }

  public function productoid($codigo)
  {
    $productos=DB::table('app_productos')->where('codigo',$codigo)->take(1)->get();
    return View::make("productos.producto")->with(array('productos'=>$productos));
  }
  public function editarproducto($codigo)
  {
    https();
      validaradministrador(1);
    $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->select('valor_lista','valor_item')->get();
    $productos=DB::table('app_productos')->where('codigo',$codigo)->take(1)->get();
    return View::make("productos.editarproducto")->with(array('productos'=>$productos,'categorias'=>$categorias));
  }

  public function editarproductointerno(Request $request)
  {
      validaradministrador(1);

    if ($request->has('editarcodigo')) {
        $this->validate($request, [
          'codigo' => 'required|unique:app_productos,codigo',
      ]);
      $codigo=$request->codigo;
    }else {
        $this->validate($request, [
        ]);
      $codigo=$request->Codigo;
    }
    if (  $request->codigoprincipal != $codigo ) {
      $linkviejo=DB::table('app_productos')->where('codigo',$request->codigoprincipal)->select('imagen')->take(1)->get();
      foreach ($linkviejo as $key) {
      $imagen=$key->imagen;
      }
      $imagenes=explode(',',$imagen);
      $public = \Storage::disk('public');
      foreach ($imagenes as $img) {
      if ($img !=0) {
        $public->move($request->codigoprincipal.'/'.$img, (int)$codigo.'/'.$img);
      }
  
      }
      $public->deleteDirectory($request->codigoprincipal);

    }

    if ($request->hasfile('imagenes')) {
      $files=$request->imagenes;
      foreach ($files as $file) {
        $public = \Storage::disk('public');
        $imageFileName = mt_rand(1,2147) . '.' . $file->getClientOriginalExtension();
        $filePath =$imageFileName;
         $public->put('/'.(int)$codigo.'/'.$filePath, file_get_contents($file), 'public');
        $link_[]=$imageFileName;
      }
    }else {
      $link_[]="";
    }
    $linkviejo=DB::table('app_productos')->select('imagen')->where('codigo',$request->codigoprincipal)->take(1)->get();
    foreach ($linkviejo as $key) {
      $imagen=$key->imagen;
    }
    if (empty($imagen)) {
    $linki=implode(",",$link_);
  }else {
    if ($link_[0]=="") {
      $linki=$imagen;
    }else {
      $linki=implode(",",$link_).','.$imagen;
    }
  }
  if ($request->has('categoria')) {
    $categoria=$request->categoria;
  }else {
    $categoria=0;
  }
  $identificador = DB::table('app_productos')->where('codigo', $request->codigoprincipal)->value('id');
  DB::table('app_productos')->where('codigo',$request->codigoprincipal)->update(['nombre'=>strtoupper($request->nombre),'codigo'=>$codigo,
  'categoria'=>$categoria,'descripcion'=>strtoupper($request->descripcion),'vl_mayorista'=>$request->vrmayorista,'vl_minorista'=>$request->vrminorista,
  'imagen'=>$linki]);

DB::table('app_movimientos')->where('producto_id',$request->codigoprincipal)->update(['producto_id'=>$codigo]);
$reserva=DB::table('app_reservas')->get();
foreach ($reserva as $value) {
  $productos=$value->producto;
  $producto=str_replace($request->codigoprincipal, $codigo, $productos);
  DB::table('app_reservas')->where('id',$value->id)->update(['producto'=>$producto]);

}

    $descripcion='nombre = '.strtoupper($request->nombre).', codigo = '.$codigo.', categoria = '.$categoria.', descripcion = '.strtoupper($request->descripcion).', vl_mayorista = '.$request->vrmayorista.', vl_minorista = '.$request->vrminorista.', imagen = '.$linki;

    DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>4,'caso'=>2,'identificador'=>$identificador,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);




  Session::flash('flash_message', 'se ha guardado el siguiente producto: ' .strtoupper($request->nombre));
  return redirect('producto/'.$codigo);
  }
public function eliminarimagen($codigo , $imagen , $ubicacion)
{
  https();
    validaradministrador(1);

  $public = \Storage::disk('public');

  $linkviejo=DB::table('app_productos')->where('codigo',$codigo)->select('imagen')->take(1)->get();
foreach ($linkviejo as $key) {
  $imagenes=$key->imagen;
}
$imagenes = str_replace($imagen.',',"", $imagenes);
$imagenes = str_replace(','.$imagen,"", $imagenes);
$imagenes = str_replace($imagen,"", $imagenes);

$public->delete('/'.$codigo.'/'.$imagen);

DB::table('app_productos')->where('codigo',$codigo)->update(['imagen'=>$imagenes]);
$descripcion='imagen = '.$imagenes;
$identificador = DB::table('app_productos')->where('codigo', $codigo)->value('id');

    DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>4,'caso'=>2,'identificador'=>$identificador,'descripcion'=>$descripcion,'ubicacion'=>str_replace("a", ",", $ubicacion),'fecha'=>Carbon::now()]]);
return back();


}
public function historico()
{
    validaradministrador(1);

 $producto=$_GET['productid'];
  $tiempo=$_GET['tiempo'];
  if (empty($tiempo)) {
    return '
    <div class="alert alert-danger alert-dismissable fade in">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Lo sentimos! </strong> Debes seleccionar un rango de tiempo
    </div>';
  }
  
  $reserva=DB::table('app_reservas')->whereIn('estado',[1, 2, 3, 4, 5,6])
  ->Where(function ($query)
   {
     $tiempo=$_GET['tiempo'];
    $fechaini=buscarfecha2(substr($tiempo, 0, 17));
    $fechafin=buscarfecha2(substr($tiempo, 18, 17));
     $query->whereBetween('desde', [$fechaini, $fechafin])
     ->orwhereBetween('hasta', [$fechaini, $fechafin]);
   })->get();

$cantidades=array();
$bodegas=array();
$clientes=array();
$desde=array();
$hasta=array();
$estado=array();

foreach ($reserva as $value) {
  $producto=$value->producto;

  $coincidencia = strpos($producto, $producto);
      if ($coincidencia !== false) {
        $produc=explode(",", $value->producto);
        $cant=explode(",", $value->cantidad);
        $position=array_search($producto, $produc);
        $cantidades[]=$cant[$position];
        $bodegas[]=$value->bodega;
        $clientes[]=$value->cliente;
        $desde[]=$value->desde;
        $hasta[]=$value->hasta;
        $estado[]=$value->estado;
     }
}
if (count($cantidades)==0) {
  return "no existe registro";
}
  return View::make("show.historico")->with(array('cantidades'=>$cantidades,'bodegas'=>$bodegas,'clientes'=>$clientes,'desde'=>$desde,'hasta'=>$hasta,'estados'=>$estado,'ciclos'=>count($cantidades)));

}


}
