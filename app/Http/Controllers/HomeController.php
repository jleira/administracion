<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.2/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;
use \Milon\Barcode\DNS1D;
use File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Storage;
use Carbon\Carbon;
use View;


/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
      $today=Carbon::today();
      $tomorrow=Carbon::tomorrow();
      $fecha_entrega= Carbon::today()->addWeeks(2);
      $fecha_despacho= Carbon::today()->addMonth();

      $reserva=DB::table('app_reservas')->whereBetween('hasta',[ $today,$tomorrow])->where('estado',4)->update(['estado'=>5]);
http();


    if (Auth::user()->id_perfil==1) {
    $entregas=DB::table('app_reservas')->where('estado',3)->whereBetween('desde',[ $today,$fecha_entrega])->select('id','cliente','desde','hasta','bodega','estado')->get();
      
      $devoluciones=DB::table('app_reservas')->where('estado',5)->whereBetween('hasta',[ $today,$tomorrow])->select('id','cliente','desde','hasta','bodega','estado')->get();
      
      $pendientes=DB::table('app_reservas')->whereIn('estado',[1,2,3,4,5])->where('hasta','<', $today)->select('id','cliente','desde','hasta','bodega')->select('id','estado','cliente','desde','hasta','bodega')->get();

      $despachos=DB::table('app_reservas')->whereIn('estado',[1,2])->whereBetween('desde',[ $tomorrow,$fecha_despacho])->select('id','cliente','desde','hasta','bodega','estado')->get();
    }

    else 
    {
          $entregas=DB::table('app_reservas')->where('estado',3)->whereBetween('desde',[ $today,$fecha_entrega])->where('bodega',Auth::user()->bodega_id)->select('id','cliente','estado','desde','hasta','bodega')->get();
      
      $devoluciones=DB::table('app_reservas')->where('estado',5)->whereBetween('hasta',[ $today,$tomorrow])->where('bodega',Auth::user()->bodega_id)->select('id','cliente','desde','hasta','estado','bodega')->get();
      
      $pendientes=DB::table('app_reservas')->whereIn('estado',[1,2,3,4,5])->where('hasta','<', $today)->where('bodega',Auth::user()->bodega_id)->select('id','cliente','desde','hasta','bodega')->select('id','estado','cliente','desde','hasta','bodega')->get();

      $despachos=DB::table('app_reservas')->whereIn('estado',[1,2])->whereBetween('desde',[ $tomorrow,$fecha_despacho])->where('bodega',Auth::user()->bodega_id)->select('id','cliente','desde','hasta','bodega','estado')->get();

    }
    

      return view('home')->with(array('entregas' =>$entregas ,'devoluciones'=>$devoluciones, 'pendientes'=>$pendientes,'despachos'=>$despachos ));
    

    }
}
 
