<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;

class SeguimientosController extends Controller
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
  public function index(){
  	validaradministrador(1);
  	 $clientes=DB::table('app_seguimiento')->where('clase',1)->orderBy('fecha','asc')->groupBy('identificador')->get();
  	 $bodegas=DB::table('app_seguimiento')->where('clase',2)->orderBy('fecha','asc')->groupBy('identificador')->get();
  	 $categorias=DB::table('app_seguimiento')->where('clase',3)->orderBy('fecha','asc')->groupBy('identificador')->get();
  	 $productos=DB::table('app_seguimiento')->where('clase',4)->orderBy('fecha','asc')->groupBy('identificador')->get();
  	 $movimientos=DB::table('app_seguimiento')->where('clase',5)->orderBy('fecha','asc')->groupBy('identificador')->get();
  	 $usuarios=DB::table('app_seguimiento')->where('clase',6)->orderBy('fecha','asc')->groupBy('identificador')->get();
 $pedidos=DB::table('app_seguimiento')->where('clase',7)->orderBy('fecha','asc')->groupBy('identificador')->get();


 return view('movimientos.index')->with(array('pedidos'=>$pedidos,'cliente'=>$clientes,'bodega'=>$bodegas,'categoria'=>$categorias,'producto'=>$productos,'movimientos'=>$movimientos,'usuarios'=>$usuarios));
   }
   public function seguimientos($clase ,$identificador){
  	validaradministrador(1);
  	 $seguimiento=DB::table('app_seguimiento')->where('clase',$clase)->orderBy('fecha','asc')->where('identificador',$identificador)->get();
  

 return view('movimientos.seguimiento')->with(array('seguimientos'=>$seguimiento));
   
    

}

}
