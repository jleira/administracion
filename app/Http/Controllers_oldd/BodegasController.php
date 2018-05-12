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

class BodegasController extends Controller
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

  public function registrar()
  {
    https();
    validaradministrador(1);
    $bodegas=DB::table('app_bodegas')->get();
    return View::make("bodegas.registrar")->with(array('bodegas'=>$bodegas));
  }

  public function registrarbodega(Request $request)
  {
    validaradministrador(1);
    $this->validate($request, ['nombre' => 'required','ciudad' => 'required','direccion' => 'required','telefono' => 'required',]);
    $todo=$request->all();
    $contador1=DB::table('app_bodegas')->where('nombre',$todo['nombre'])->where('ciudad',$todo['ciudad'])->count();
    $contador2=DB::table('app_bodegas')->where('ciudad',$todo['ciudad'])->where('direccion',$todo['direccion'])->count();
    if ($contador1>0 or $contador2>0) {
      Session::flash('flash_message', 'Ya existe una bodega con estas catracteristicas');
      return back();
      }
 $descripcion='nombre= '.strtoupper($todo['nombre']).',ciudad = '.strtoupper($todo['ciudad']).', direccion = '.strtoupper($todo['direccion']).', telefono = '.$todo['telefono'];

  DB::table('app_bodegas')->insert([['nombre'=>strtoupper($todo['nombre']),'ciudad'=>strtoupper($todo['ciudad']),'direccion'=>strtoupper($todo['direccion']),'telefono'=>$todo['telefono']]]);
  Session::flash('flash_message', 'se ha guardado la bodega: ' .strtoupper($todo['nombre']));

  $idf=DB::table('app_bodegas')->max('id');

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>$idf,'clase'=>2,'caso'=>1,'identificador'=>$idf,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);

  return back();
  }
  public function editarbodega()
  {
    http();
    validaradministrador(1);
    $bodegas=DB::table('app_bodegas')->get();
    return View::make("bodegas.edits")->with(array('bodegas'=>$bodegas));
  }
  public function editar(Request $request)
  {
    DB::table('app_bodegas')->where('id', $request->id)->update(['nombre' => strtoupper($request->nombre),
    'ciudad'=>strtoupper($request->ciudad),'direccion'=>strtoupper($request->direccion),'telefono'=>$request->telefono]);
    $descripcion='nombre = '.strtoupper($request->nombre).',ciudad = '.strtoupper($request->ciudad).',direccion = '.strtoupper($request->direccion).',telefono = '.$request->telefono;
   
      DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>$request->id,'clase'=>2,'caso'=>2,'identificador'=>$request->id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);

    return back();
  }
  public function actualizarbodega($id)
  {
    https();
    validaradministrador(1);
    $bodegas=DB::table('app_bodegas')->where('id',$id)->get();
    return View::make("bodegas.editarbodega")->with(array('bodega'=>$bodegas));
  }

}
