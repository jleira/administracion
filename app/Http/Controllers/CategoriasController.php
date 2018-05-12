<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use Auth;
use Carbon\Carbon;


class CategoriasController extends Controller
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
   public function registrarcategoria()
   {
    https();
    validaradministrador(2);
     $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
     return View::make("categorias.registrarcategoria")->with(array('categorias'=>$categorias));
   }
   public function editarc()
   {
    validaradministrador(2);
     $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
     return View::make("categorias.editarcategorias")->with(array('categorias'=>$categorias));
   }
   public function crear(Request $request)
   {
        validaradministrador(2);
     $this->validate($request, [
       'nombre' => 'required',
       'descripcion'=>'required',
       'codigo'=>'required|numeric|min:10|unique:app_listas,valor_lista',
   ]);

   DB::table('app_listas')->insert([['valor_lista'=>$request->codigo,'valor_item'=>$request->nombre,'descripcion'=>$request->descripcion,'id_tipo_lista'=>4]]);

  $descripcion='codigo = '.$request->codigo.', nombre = '.$request->nombre.',descripcion = '.$request->descripcion;

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>3,'caso'=>1,'identificador'=>$request->codigo,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);


   return back();
   }
   public function editar($id)
   {
    https();
        validaradministrador(2);
     $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->where('valor_lista',$id)->get();
     return View::make("categorias.editarcategoria")->with(array('categorias'=>$categorias));
   }
   public function editarcategoria(Request $request)
   {
     $this->validate($request, [
       'nombre' => 'required',
       'descripcion'=>'required',
   ]);
   DB::table('app_listas')->where('id_tipo_lista',4)->where('valor_lista',$request->id)->update(['valor_item'=>$request->nombre,'descripcion'=>$request->descripcion]);

     $descripcion='nombre = '.$request->nombre.',descripcion = '.$request->descripcion;
DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>3,'caso'=>2,'identificador'=>$request->id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);


return redirect('categoria/'.$request->id);
//    'codigo'=>'required|numeric|min:10|unique:app_listas,valor_lista',
   }
   public function buscar()
   {
    http();
        validaradministrador(2);
     $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
     return View::make("categorias.buscar")->with(array('categorias'=>$categorias));
   }
   public function categoria($id)
   {
        validaradministrador(2);
     $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->where('valor_lista',$id)->get();
     return View::make("categorias.ver")->with(array('categorias'=>$categorias));
   }
   public function act()
   {
        validaradministrador(2);
     $categorias=DB::table('app_listas')->where('id_tipo_lista',4)->get();
     foreach ($categorias as $categoria) {
       $productos=DB::table('app_productosinicial')->where('categoria',$categoria->valor_lista)->get();
       $codigo=($categoria->valor_lista)*100;
      foreach ($productos as $producto) {
        DB::table('app_productosinicial')->where('id',$producto->id)->update(['codigo'=>$codigo]);
        $codigo=$codigo+1;
       }
     }
   }
}
