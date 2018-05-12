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

class GeneralesController extends Controller
{
    //
  public function index()
  {
    return view('generales/index');
  }
    public function cambiarpass($pass)
  {
    return pass($pass);
  }
  public function actualizar(Request $request)
  {
if (Auth::guest()) {
   header('Location: http://'.$_SERVER['HTTP_HOST']);
}else{
    if (Auth::user()->id==1) {
   if ($request->hasfile('logo')) {
      $logo=$request->logo;
        $public = \Storage::disk('public');
        $imageFileName = 'logo' . '.' . $logo->getClientOriginalExtension();
        $filePath = $imageFileName;
         $public->put('/'.$filePath, file_get_contents($logo), 'public');
      }else {
        $imageFileName="logo.png";
      }

      DB::table('app_generales')->where('id',1)->update([
        'nombre'=>$request->nombre,'logo'=>$imageFileName,'color'=>$request->color
      ]);
      return back();
    }else{
         header('Location: http://'.$_SERVER['HTTP_HOST']);
    }
  }

    }
 


}
