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

class EstadosController extends Controller
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

  public function cambiarestado(Request $request)
  {
  validaradministrador(2);
      if ($request->has('confirmar_despacho')) {
      $reserva=DB::table('app_reservas')->where('id',$request->reserva)->update(['estado'=>3]);
      return back();
    }

    if ($request->has('cancelarpedido')) {
      $reserva=DB::table('app_reservas')->where('id',$request->reserva)->update(['estado'=>7]);
      return back();
    }
    if ($request->has('cancelarpedido')) {
      $reserva=DB::table('app_reservas')->where('id',$request->reserva)->update(['estado'=>7]);
      return back();
    }
    if ($request->has('confirmarpedido')) {
      $reserva=DB::table('app_reservas')->where('id',$request->reserva)->update(['estado'=>2]);
      return back();
    }
    if ($request->has('entregado')) {
      $reserva=DB::table('app_reservas')->where('id',$request->reserva)->update(['estado'=>4]);
      return back();
    }
    if ($request->has('recoger')) {
      $reserva=DB::table('app_reservas')->where('id',$request->reserva)->update(['estado'=>5]);
      return back();
    }
    if ($request->has('recogidaexitosa')) {
      $reserva=DB::table('app_reservas')->where('id',$request->reserva)->update(['estado'=>6]);
      return back();
    }
        if ($request->has('aceptarcotizacion')) {
      $reserva=DB::table('app_reservas')->where('id',$request->reserva)->update(['estado'=>1]);
      return back();
    }
  }
  public function actualizarestado()
  {
    $reservas=DB::table('app_reservas')->whereIn('estado',[1, 2, 3, 4, 5])->get();
    $fechahoy=Carbon::today();
    $fecha15despues=$fechahoy->addDays(15);
    foreach ($resevas as $reserva) {
      $fecha=substr($reserva->desde,0,10)." 00:00:00";
      if ($fecha15despues==$fecha and $reserva->estado==1) {
        DB::table('app_reservas')->where('id',$reserva->id)->update(['estado'=>7]);
      }elseif ($fecha15despues==$fecha and $reserva->estado==2) {
        DB::table('app_reservas')->where('id',$reserva->id)->update(['estado'=>3]);
      }elseif ($fechahoy==$fecha and $reserva->estado==1) {
        DB::table('app_reservas')->where('id',$reserva->id)->update(['estado'=>7]);
      }elseif ($fechahoy==$fecha and $reserva->estado==2) {
        DB::table('app_reservas')->where('id',$reserva->id)->update(['estado'=>3]);
      }
    }
  }


}
