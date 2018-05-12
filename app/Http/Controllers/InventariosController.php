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
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Mail;

  class InventariosController extends Controller
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

    public function inventarioform()
    {
      http();
      validaradministrador(2);
      $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
      $productos=DB::table('app_productos')->get();
      $bodegas=DB::table('app_bodegas')->get();
      return View('inventario.inventarioform')->with(array('categorias'=>$categorias,'productos'=>$productos,'bodegas'=>$bodegas));
    }

        public function imprimir(Request $request)
    {
            http();
        ini_set('max_execution_time', 180);
 
     validaradministrador(2);
     if ($request->button==1) {
      $categoria=$request->categoria;
      $bodega=$request->bodega;
      $fecha_inicio=$request->fecha_inicio;
      $fecha_fin=$request->fecha_fin;
      $productos=DB::table('app_productos')->where('categoria',$categoria)->select('nombre','codigo','vl_unitario')->get();
      $view =  \View::make('pdf.porcategoria')->with(array('productos' =>$productos,'categoria' =>$categoria,'bodega'=>$bodega,'fecha_inicio'=>$fecha_inicio,'fecha_fin'=>$fecha_fin))->render();

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream('Inventario');

    }elseif ($request->button==2) {
      $categoria=explode(',',$request->categoria);
      $bodega=$request->bodega;
      $fecha_inicio=$request->fecha_inicio;
      $fecha_fin=$request->fecha_fin;
      $productos=DB::table('app_productos')->whereIn('categoria',$categoria)->select('nombre','codigo','vl_unitario','categoria')->get();
      $view =  \View::make('pdf.porbodega')->with(array('productos' =>$productos,'categoria' =>$categoria,'bodega'=>$bodega,'fecha_inicio'=>$fecha_inicio,'fecha_fin'=>$fecha_fin))->render();

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream('invoice');

    }
    elseif ($request->button==3) {
      $bodega=$request->bodega;
      $fecha_inicio=$request->fecha_inicio;
      $fecha_fin=$request->fecha_fin;
      $pr=explode(",", $request->product);
      $productos=DB::table('app_productos')->whereIn('codigo',$pr)->select('nombre','codigo','vl_unitario')->get();
      $view =  \View::make('pdf.porproducto')->with(array('productos' =>$productos,'bodega'=>$bodega,'fecha_inicio'=>$fecha_inicio,'fecha_fin'=>$fecha_fin))->render();

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream('invoice');

    }elseif ($request->button==4) {
      $categoria=$request->categoria;
      $bodega=$request->bodega;
      $fecha_inicio=$request->fecha_inicio;
      $fecha_fin=$request->fecha_fin;
      $productos=DB::table('app_productos')->where('categoria',$categoria)->select('nombre','codigo','vl_unitario')->get();
      Excel::create(Carbon::now(), function($excel) use($productos,$categoria, $bodega, $fecha_inicio, $fecha_fin) {
               $excel->sheet('Sheet 1', function($sheet) use($productos,$categoria, $bodega, $fecha_inicio, $fecha_fin) {
              $sheet->loadView('pdf.porcategoriaexcel', array('productos' =>$productos,'categoria' =>$categoria,'bodega'=>$bodega,'fecha_inicio'=>$fecha_inicio,'fecha_fin'=>$fecha_fin));
                   $sheet->setOrientation('landscape');
        });
           })->export('xls');

    }elseif ($request->button==5) {
      $categoria=explode(',',$request->categoria);
      $bodega=$request->bodega;
      $fecha_inicio=$request->fecha_inicio;
      $fecha_fin=$request->fecha_fin;
      $productos=DB::table('app_productos')->whereIn('categoria',$categoria)->select('nombre','codigo','vl_unitario','categoria')->get();

        Excel::create(Carbon::now(), function($excel) use($productos,$categoria, $bodega, $fecha_inicio, $fecha_fin) {
               $excel->sheet('Sheet 1', function($sheet) use($productos,$categoria, $bodega, $fecha_inicio, $fecha_fin) {
              $sheet->loadView('pdf.porbodegaexcel', array('productos' =>$productos,'categoria' =>$categoria,'bodega'=>$bodega,'fecha_inicio'=>$fecha_inicio,'fecha_fin'=>$fecha_fin));
                   $sheet->setOrientation('landscape');
        });
           })->export('xls');
    }elseif ($request->button==6) {
       $bodega=$request->bodega;
      $fecha_inicio=$request->fecha_inicio;
      $fecha_fin=$request->fecha_fin;
      $pr=explode(",", $request->product);
      $productos=DB::table('app_productos')->whereIn('codigo',$pr)->select('nombre','codigo','vl_unitario')->get();

        Excel::create(Carbon::now(), function($excel) use($productos, $bodega, $fecha_inicio, $fecha_fin) {
               $excel->sheet('Sheet 1', function($sheet) use($productos, $bodega, $fecha_inicio, $fecha_fin) {
              $sheet->loadView('pdf.porproductoexcel', array('productos' =>$productos,'bodega'=>$bodega,'fecha_inicio'=>$fecha_inicio,'fecha_fin'=>$fecha_fin));
                   $sheet->setOrientation('landscape');
        });
           })->export('xls');
    }
  }


    public function inventario(Request $request)
    {
        ini_set('max_execution_time', 180);
     validaradministrador(2);
   
$this->validate($request, [
        'fecha' => 'required',
        ]);
    $fechaini=buscarfechainventario($request->fecha) . " 00:00:00";
    $fechafin=$fechaini;
    if ($request->has('todasfecha')) {
      $this->validate($request, [
        'fecha_final' => 'required',
        ]);
      $fechafin=buscarfechainventario($request->fecha_final) . " 00:00:00";
    }

if ($request->has('porcategoria')) {

if ($request->has('todascategorias')) {
$categoriasabuscar=DB::table('app_listas')->where('id_tipo_lista',4)->select('valor_lista')->get();
foreach ($categoriasabuscar as $value) {
  $categoriasmostrar[]=$value->valor_lista;
  }
if($request->has('todasb')){
      $bodegasbuscar=DB::table('app_bodegas')->select('id')->get();
      foreach ($bodegasbuscar as $value) {
        $bodegas[]=$value->id;
      }

}else{
$this->validate($request, [
        'bodega' => 'required',
        ]);
$bodegas=$request->bodega;

}
}else{
    $categoriasmostrar=$request->categoria;
    $categoriasabuscar=DB::table('app_listas')->where('id_tipo_lista',4)->whereIn('valor_lista',$request->categoria)->select('valor_lista')->get();
  $this->validate($request, [
        'categoria' => 'required',
        ]);
  if($request->has('todasb')){
  $bodegasbuscar=DB::table('app_bodegas')->select('id')->get();
      foreach ($bodegasbuscar as $value) {
        $bodegas[]=$value->id;
      }
}else{
$this->validate($request, [
        'bodega' => 'required',
        ]);
$bodegas=$request->bodega;
}
}
$producto=DB::table('app_productos')->whereIn('categoria',$categoriasmostrar)->select('codigo','nombre','categoria','vl_unitario')->get();
if ($request->bod==2) {
  if ($request->has('todasb')) {
    $todas=1;
  }else{
    $todas=2;
  }
return View::make('inventario.porcategorias')->with(array('productos' =>$producto,'bodegas'=>$bodegas,'categorias'=>$categoriasabuscar,'todasbodegas'=>$todas,'fecha_inicio'=>$fechaini,'fecha_fin'=>$fechafin));
}else{
  $cat=implode(',',$categoriasmostrar);
return View::make('inventario.porcategorias2')->with(array('productos' =>$producto,'bodegas'=>$bodegas,'categorias'=>$categoriasabuscar,'fecha_inicio'=>$fechaini,'fecha_fin'=>$fechafin,'cat'=>$cat));
}
}else{
  if($request->has('todasp')){
  $productos=DB::table('app_productos')->select('nombre','codigo','categoria','vl_unitario')->get();
  foreach ($productos as $value) {
    $producto[]=$value->codigo;
  }

}else{
$this->validate($request, [
        'producto' => 'required',
        ]);
$producto=$request->producto;
  $productos=DB::table('app_productos')->whereIn('codigo',$producto)->select('nombre','codigo','categoria','vl_unitario')->get();
}
  if($request->has('todasb')){
        $todas=1;
  $bodegasb=DB::table('app_bodegas')->select('id')->get();
      foreach ($bodegasb as $value) {
        $bodegas[]=$value->id;
      }
}else{
      $todas=2;
$this->validate($request, [
        'bodega' => 'required',
        ]);
$bodegas=$request->bodega;
}
  $producto=implode(',', $producto);
return View::make('inventario.porproductos')->with(array('productos' =>$productos,'bodegas'=>$bodegas,'todasbodegas'=>$todas,'fecha_inicio'=>$fechaini,'fecha_fin'=>$fechafin,'p'=>$producto));

}
}
  public function imprimir2(){
   
    if ($_GET['o']==2) {
      return $view =  \View::make('pdf.fact')->with(array())->render();
    }
      $view =  \View::make('pdf.fact')->with(array())->render();

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream('invoice'); 
  }
}
