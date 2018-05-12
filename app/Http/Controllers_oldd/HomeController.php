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
http();
    if (Auth::user()->id_perfil==1) {
      $entregas=DB::table('app_reservas')->where('estado',3)->whereBetween('desde',[ Carbon::today(),Carbon::tomorrow()])->get();
      $devoluciones=DB::table('app_reservas')->where('estado',5)->whereBetween('hasta',[ Carbon::today(),Carbon::tomorrow()])->get();
      $pendientes=DB::table('app_reservas')->whereIn('estado',[3, 5])->where('hasta','<', Carbon::today())->get();
    }else {
      $entregas=DB::table('app_reservas')->where('estado',3)->whereBetween('desde',[ Carbon::today(),Carbon::tomorrow()])->where('bodega',Auth::user()->bodega_id)->get();
      $devoluciones=DB::table('app_reservas')->where('estado',5)->whereBetween('hasta',[ Carbon::today(),Carbon::tomorrow()])->where('bodega',Auth::user()->bodega_id)->get();
      $pendientes=DB::table('app_reservas')->whereIn('estado',[3, 5])->where('hasta','<', Carbon::today())->where('bodega',Auth::user()->bodega_id)->get();
    }
    

      return view('home')->with(array('entregas' =>$entregas ,'devoluciones'=>$devoluciones, 'pendientes'=>$pendientes ));
    }
}
