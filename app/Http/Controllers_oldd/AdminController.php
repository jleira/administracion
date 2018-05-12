<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use Auth;
use Session;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function usuarios()
    {
    validaradministrador(1);
    $usuarios=DB::table('users')->whereNotIn('id',[1])->get();
    return View::make("admin.usuarios")->with(array('usuarios'=>$usuarios));
    }
    public function registrarusuario()
    {
      https();
      validaradministrador(1);
      $perfiles=DB::table('app_listas')->where('id_tipo_lista',2)->get();
      $bodegas=DB::table('app_bodegas')->get();
      return View::make("auth.register")->with(array('perfiles'=>$perfiles,'bodegas'=>$bodegas));
    }
        public function editarusuario($id)
    {
            https();

      validaradministrador(1);
      if ($id==1) {
        return redirect('home');
      }
      $perfiles=DB::table('app_listas')->where('id_tipo_lista',2)->get();
      $bodegas=DB::table('app_bodegas')->get();
      $user=DB::table('users')->where('id',$id)->get();
      return View::make("auth.editar")->with(array('perfiles'=>$perfiles,'bodegas'=>$bodegas,'user'=>$user));
    }

    public function editar(Request $request)
    {
      validaradministrador(1);
      if ($request->id==1) {
        return redirect('home');
      }
      if ($request->id_perfil==1) {
      if ($request->has('editaremail')) {
        $this->validate($request, ['name' => 'required|max:255','email' => 'required|email|max:255|unique:users','id_perfil' => 'required',]);

      }if ($request->has('editarcontrasena')) {
        $this->validate($request, ['name' => 'required|max:255','password' => 'required|confirmed|min:6','id_perfil' => 'required',]);
      }
      if ($request->has('editaremail')) {
      
       DB::table('users')->where('id',$request->id)->update(['name'=>$request->name,'id_perfil'=>$request->id_perfil,'telefono'=>$request->telefono,'email'=> $request->email,'bodega_id'=>0 ]);

        $descripcion='nombre = '.$request->name.', id_perfil = SUPERUSUARIO, telefono = '.$request['telefono'].', email = '. $request['email'];
        
        DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>6,'caso'=>2,'identificador'=>$request->id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);

      }

      if ($request->has('editarcontrasena')) {
      DB::table('users')->where('id',$request->id)->update(['name'=>$request->name,'password'=> bcrypt($request['password']),'id_perfil'=>$request['id_perfil'],'telefono'=>$request['telefono'],'bodega_id'=>0 ]);
      
        $descripcion='nombre = '.$request->name.', id_perfil = SUPERUSUARIO, telefono = '.$request['telefono'].', pass = '.$request['password'];
        
        DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>6,'caso'=>2,'identificador'=>$request->id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);

      }
      if (!($request->has('editaremail') and $request->has('editarcontrasena'))) {
       
      $this->validate($request, ['name' => 'required|max:255','id_perfil' => 'required',]);
       DB::table('users')->where('id',$request->id)->update(
      ['name'=>$request->name,'id_perfil'=>$request['id_perfil'],'telefono'=>$request['telefono'],'bodega_id'=>0 ]);
      
      $descripcion='nombre = '.$request->name.', id_perfil = SUPERUSUARIO, telefono = '.$request['telefono'];
        
        DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>6,'caso'=>2,'identificador'=>$request->id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);


      }

      Session::flash('flash_message', 'Usuario editado existoasmente.');
      return redirect('usuarios');
      }
      elseif ($request->id_perfil==2) {
              if ($request->has('editaremail')) {
        $this->validate($request, ['name' => 'required|max:255','email' => 'required|email|max:255|unique:users','id_perfil' => 'required','bodega'=>'required',]);
      }if ($request->has('editarcontrasena')) {
        $this->validate($request, ['name' => 'required|max:255','password' => 'required|confirmed|min:6','id_perfil' => 'required','bodega'=>'required',]);
      }
       if ($request->has('editaremail')) {
       DB::table('users')->where('id',$request->id)->update(['name'=>$request->name,'id_perfil'=>$request['id_perfil'],'telefono'=>$request['telefono'],'email'=> $request['email'],'bodega_id'=>$request->bodega ]);
  
        $descripcion='nombre = '.$request->name.', id_perfil = adminbodega, telefono = '.$request['telefono'].', email = '. $request['email'].', bodega_id = '.validarbodega($request->bodega) ;

        DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>6,'caso'=>2,'identificador'=>$request->id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);

      }if ($request->has('editarcontrasena')) {
            DB::table('users')->where('id',$request->id)->update(['name'=>$request->name,'password'=> bcrypt($request['password']),'id_perfil'=>$request['id_perfil'],'telefono'=>$request['telefono'],'bodega_id'=>$request->bodega ]);
                  $descripcion='nombre = '.$request->name.', id_perfil = adminbodega, telefono = '.$request['telefono'].', pass = '. $request['password'].', bodega_id = '.validarbodega($request->bodega) ;

        DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>6,'caso'=>2,'identificador'=>$request->id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);
      }
            if (!($request->has('editaremail') and $request->has('editarcontrasena'))) {
       
      $this->validate($request, ['name' => 'required|max:255','id_perfil' => 'required','bodega'=>'required',]);
       DB::table('users')->where('id',$request->id)->update(
      ['name'=>$request->name,'id_perfil'=>$request['id_perfil'],'telefono'=>$request['telefono'],'bodega_id'=>$request->bodega ]);
              $descripcion='nombre = '.$request->name.', id_perfil = adminbodega, telefono = '.$request['telefono'].', bodega_id = '.validarbodega($request->bodega) ;

        DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>6,'caso'=>2,'identificador'=>$request->id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);
      }

      Session::flash('flash_message', 'Usuario editado existoasmente.');
      return redirect('usuarios');
      }

    }

    public function registrarusuariof(Request $request)
    {
      validaradministrador(1);
      if ($request->id_perfil==1) {
        $this->validate($request, ['name' => 'required|max:255',  'email' => 'required|email|max:255|unique:users',
      'password' => 'required|confirmed|min:6','id_perfil' => 'required',]);
      DB::table('users')->insert([
      ['name'=>$request->name,'password'=> bcrypt($request['password']),'id_perfil'=>$request['id_perfil'],'telefono'=>$request['telefono'],
       'email'=> $request['email'],'bodega_id'=>0 ]
     ]);

  $descripcion='nombre = '.$request->name.', pass = '. $request['password'].', id_perfil = SUPERUSUARIO, telefono = '.$request['telefono'].', email = '. $request['email'];

  $idf=DB::table('users')->max('id');

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>6,'caso'=>1,'identificador'=>$idf,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);

      $usuarios=DB::table('users')->get();
        Session::flash('flash_message', 'Usuario creado existoasmente.');
        return redirect('usuarios');

      }elseif ($request->id_perfil==2) {
        $this->validate($request, ['name' => 'required|max:255',  'email' => 'required|email|max:255|unique:users',
      'password' => 'required|confirmed|min:6','id_perfil' => 'required',]);
      if ($request->bodega==0) {
        Session::flash('error_message', 'Debes seleccionar una bodega');
        return back();
      }
      DB::table('users')->insert([
      ['name'=>$request->name,'password'=> bcrypt($request['password']),'id_perfil'=>$request['id_perfil'],'telefono'=>$request['telefono'],
       'email'=> $request['email'],'bodega_id'=>$request->bodega ]
     ]);
        $descripcion='nombre = '.$request->name.', pass = '. $request['password'].', id_perfil = adminbodega, telefono = '.$request['telefono'].', email = '. $request['email'].", bodega_id = ".validarbodega($request->bodega);

  $idf=DB::table('users')->max('id');

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>$request->bodega,'clase'=>6,'caso'=>1,'identificador'=>$idf,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);

     $usuarios=DB::table('users')->get();
       Session::flash('flash_message', 'Usuario creado existoasmente.');
        return redirect('usuarios');
      }

    }

}
