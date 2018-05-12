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


class PedidosController extends Controller
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
  public function index()
  {
    https();
     validaradministrador(2);
     if (Auth::user()->id_perfil==1) {
    $reservas=DB::table('app_reservas')->get();
     }else{
       $reservas=DB::table('app_reservas')->where('bodega',Auth::user()->bodega_id)->get(); 
     }
  
    return View::make("pedidos.buscar")->with(array('reservas'=>$reservas));
  }
    public function registrarpedido()
    {
            https();
        validaradministrador(2);

      $productos=DB::table('app_productos')->select('codigo','nombre')->get();
      $clientes=DB::table('app_clientes')->select('id','nombre','apellido')->get();
      $bodegas=DB::table('app_bodegas')->select('id','nombre')->get();
      return View::make("pedidos.registrar")->with(array('productos'=>$productos,'clientes'=>$clientes,'bodegas'=>$bodegas));
    }
    public function generarguia(Request $request)
    {

              validaradministrador(2);

      $reserva=DB::table('app_reservas')->where('id',$request->reserva)->get();
      $factura=DB::table('app_facturaciones')->where('id_reserva',$request->reserva)->orderby('id','desc')->take(1)->get();

      if ($request->generarfactura==1) {
        $id=$request->reserva;
        $view =  \View::make('pdf.factura')->with(array('orden' =>$reserva,'factura'=>$factura))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('invoice');
        }elseif ($request->generarfactura==2) {
          if (validarcertificado()==1) {
      Session::flash('error_message', 'Para generear un archivo excel debes cambiar https por http en el link' );
      return back();
          }
           Excel::create($request->reserva , function($excel) use($reserva) {
               $excel->sheet('Sheet 1', function($sheet) use($reserva) {
                $id=$_POST['reserva'];
          $reserva=DB::table('app_reservas')->where('id',$id)->get();
          $factura=DB::table('app_facturaciones')->where('id_reserva',$id)->orderby('id','desc')->take(1)->get();
                  $sheet->loadView('pdf.excel', array('orden' =>$reserva,'factura'=>$factura));

     $sheet->setOrientation('landscape');
        });
           })->export('xls');
    }elseif ($request->generarfactura==3) {
         $id=$request->reserva;
        $view =  \View::make('pdf.pagos')->with(array('orden' =>$reserva,'factura'=>$factura))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('invoice');

    }
  }
  public function cambiarestado(Request $request)
  {
$reserva=DB::table('app_reservas')->where('id',$request->reserva)->update(['estado'=>$request->estado+1]);
return back();
  }
    public function crearpedido(Request $request)
    {
              validaradministrador(2);
      if ($request->has('cotizar')) {
        $this->validate($request, [
          'cantidad' => 'required|min:1',
          'tiempo' => 'required',
          'direccion'=>'required',
        ]);
        $iva=$request->has('iva');
        if ($iva) {
          $iva=1;
        }else{
          $iva=0;
        }
        $tiempo=$request->tiempo; 
        $fechaini=buscarfecha4(substr($tiempo, 0, 19));
        $fechafin=buscarfecha4(substr($tiempo, 22, 19));
        $cantidad=$request->cantidad;
        $productos=$request->producto;
        $bodega=$request->bodega_item;
        $productosguardar=implode(",", $productos);
        $cantidadguardar=implode(",",$cantidad);
        if ($request->has('Concepto')) {
          $concepto_descuentos=implode(",", $request->Concepto);
          $descuentos=implode(",", $request->descuento);
          $descuentos=str_replace('.','',$descuentos);
        }else {
          $concepto_descuentos="";
          $descuentos="";
        }
        if ($request->has('conceptoimpuesto')) {
          $concepto_impuesto=implode(",", $request->conceptoimpuesto);
          $impuesto=implode(",", $request->impuesto);
          $impuesto=str_replace('.','',$impuesto);
        }else {
          $concepto_impuesto="";
          $impuesto="";
        }

        if ($request->has('fecha_evento')) {
          $fecha_evento=buscarfecha5($request->fecha_evento);
        }else {
          $fecha_evento=$fechaini;
        }
        DB::table('app_cotizaciones')->insert([['fecha_evento'=>$fecha_evento,'desde'=>$fechaini,'hasta'=>$fechafin,
        'producto'=>$productosguardar,'cantidad'=>$cantidadguardar,'bodega'=>$bodega,
        'estado'=>$request->estado,'cliente'=>$request->cliente,'recepcion'=>$request->direccion,
        'concepto_descuento'=>$concepto_descuentos,'descuento'=>$descuentos,'concepto_impuesto'=>$concepto_impuesto,
        'impuesto'=>$impuesto,'iva'=>$iva]]);
        $id=DB::table('app_cotizaciones')->max('id');
        $reserva=DB::table('app_cotizaciones')->where('id',$id)->get();
        $view =  \View::make('pdf.guia')->with(array('orden' =>$reserva))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf = PDF::loadView('pdf.guia',array('orden'=>$reserva));
        return $pdf->stream();
        $pdf->loadHTML($view);
        $pdf->stream();

      }
      $this->validate($request, [
        'cantidad' => 'required',
        'tiempo' => 'required',
        'direccion'=>'required',
        'bodega_item'=>'required',
        'abono'=>'required',
        'cliente'=>'required',
      ]);
      $abono=str_replace('.','',$request->abono);
      $tiempo=$request->tiempo;
      $fechaini=buscarfecha3(substr($tiempo, 0, 19));
      $fechafin=buscarfecha3(substr($tiempo, 22, 19));

      $cantidad=$request->cantidad;
      $productos=$request->producto;
      $bodega=$request->bodega_item;
      for ($i=0; $i < count($request->producto) ; $i++) {
        $totalp="";
        $total=DB::table('app_movimientos')->where('producto_id', $productos[$i])->where('bodega',$bodega )->take(1)->orderby('creado','desc')->get();
        foreach ($total as $value) {
          $totalp=$value->total;
        }
        if (empty($total)) {
          Session::flash('error_message', 'El producto '. validarproductoporcodigo($productos[$i]). " no registra suficientes unidades disponibles" );
          return back();
        }
        $disponible=DB::table('app_reservas')
        ->whereIn('estado',[1, 2, 3, 4, 5])
        ->Where(function ($query)
         {
           $tiempo=$_POST['tiempo'];
           $fechaini=buscarfecha3(substr($tiempo, 0, 19));
           $fechafin=buscarfecha3(substr($tiempo, 22, 19));
           $query->whereBetween('desde', [$fechaini, $fechafin])
           ->orwhereBetween('hasta', [$fechaini, $fechafin]);
         })->get();
        foreach ($disponible as $value) {
          $cantidadinicial=0;
          $productosre=$value->producto;
          $coincidencia = strpos($productosre, $productos[$i]);
          if (!($coincidencia === false)) {
           $produc=explode(",", $value->producto);
           $cant=explode(",", $value->cantidad);
           $position=array_search($productos[$i], $produc);
           $cantidadinicial=$cantidadinicial+$cant[$position];//aqui esta la cantidad reservada
           $totalp=$totalp-$cantidadinicial;
    if ($cantidad[$i]> $totalp) {
//      Session::flash('error_message', 'Solo exiten '.$totalp.' '. validarproductoporcodigo($productos[$i]). ' disponibles entre estas fechas' );
//      return back();
      }
        }
        }
      }
      $productosguardar=implode(",", $productos);
      $cantidadguardar=implode(",",$cantidad);

      if ($request->has('Concepto')) {
        $this->validate($request, [
          'Concepto' => 'required',
        ]);
        $concepto_descuentos=implode(",", $request->Concepto);
        $descuentos=implode(",", $request->descuento);
        $descuentos=str_replace('.','',$descuentos);
        $descu=explode(',', $descuentos);
        $des=array_sum($descu);
      }else {
        $concepto_descuentos="";
        $descuentos="";
        $descu=0;
        $des=0;
      }
      if ($request->has('conceptoimpuesto')) {
        $concepto_impuesto=implode(",", $request->conceptoimpuesto);
        $impuesto=implode(",", $request->impuesto);
        $impuesto=str_replace('.','',$impuesto);
        $impu=explode(',', $impuesto);
        $imp=array_sum($impu);

      }else {
        $concepto_impuesto="";
        $impuesto="";
        $impu=0;
        $imp=0;

      }

        if ($request->has('fecha_evento')) {
          $fecha_evento=buscarfecha5($request->fecha_evento);
        }else {
          $fecha_evento=$fechaini;
        }

      $totalfacturado=valorfactura($productosguardar,$cantidadguardar,$request->cliente);
        $iva=$request->has('iva');
        if ($iva) {
          $iva=1;
          (float)$cobrariva=$totalfacturado*0.19;
          (float)$total_facturado=$totalfacturado+$cobrariva;
        }else{
          $iva=0;
          (float)$total_facturado=$totalfacturado;
        }
   
    
       (float)$totalrestante=$total_facturado-$abono-$des+$imp;
       
      DB::table('app_reservas')->insert([['fecha_evento'=>$fecha_evento,'desde'=>$fechaini,'hasta'=>$fechafin,
      'producto'=>$productosguardar,'cantidad'=>$cantidadguardar,'bodega'=>$bodega,
      'estado'=>$request->estado,'recepcion'=>$request->direccion,'cliente'=>$request->cliente]]);
      $id = DB::table('app_reservas')->max('id');
      DB::table('app_facturaciones')->insert([['id_reserva'=>$id,'concepto_descuentos'=>$concepto_descuentos,
      'descuentos'=>$descuentos,'concepto_impuestos'=>$concepto_impuesto,'impuestos'=>$impuesto,
      'fecha_abono'=>Carbon::now(),'total_abonado'=>$abono,'abono'=>$abono,'total_facturado'=>$totalfacturado,'total_restante'=>$totalrestante,'iva'=>$iva]]);

       $descripcion='fecha_evento = '.$fecha_evento. 'desde = '.$fechaini.', hasta = '.$fechafin.', producto = '.$productosguardar.', cantidad = '.$cantidadguardar.', bodega = '.$bodega.', estado= '.validarlista($request->estado,6).', recepcion = '.$request->direccion.', cliente = '.cliente($request->cliente,1).', id_reserva = '.$id.' , concepto_descuentos = '.$concepto_descuentos.', descuentos = '.$descuentos.', concepto_impuestos = '.$concepto_impuesto.', impuestos = '.$impuesto.', fecha_abono = '.Carbon::now().', total_abonado = '.$abono.', abono = '.$abono.', total_facturado = '.$totalfacturado.', total_restante = '.$totalrestante.', iva ='.$iva;

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>7,'caso'=>1,'identificador'=>$id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);




      Session::flash('flash_message', 'Reserva exitosa' );
      return redirect('orden/'.$id);

    }

    public function confirmar()
    {

    validaradministrador(2);
      $producto=$_GET['productid'];
      $cantidad=$_GET['cantidad'];
      $bodega=$_GET['bodega'];
      $tiempo=$_GET['tiempo'];
      $caso=$_GET['caso'];
  if (empty($tiempo)) {
    return '
    <div class="alert alert-danger alert-dismissable fade in">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Lo sentimos! </strong> Debes seleccionar un rango de tiempo
    </div>';
  }
  $fechaini=buscarfecha(substr($tiempo, 0, 10));
  $fechafin=buscarfecha(substr($tiempo, 11, 10));
  $productoscod=str_replace("-",",",$producto);
  $productoscod=substr($productoscod, 0, -1);
  $productoscod=explode(",",$productoscod);
  for ($i=0; $i < count($productoscod) ; $i++) {
  $ini=0;

  $total=DB::table('app_movimientos')->where('producto_id', $productoscod[$i])->where('bodega',$bodega )->take(1)->orderby('creado','desc')->get();
  $productosf=DB::table('app_productos')->where('codigo', $productoscod[$i])->take(1)->get();
  foreach ($productosf as $value) {
    $productosfinal[]=$value->nombre;
    $codigosf[]=$value->codigo;
  }
  foreach ($total as $value) {
    $ini=$value->total;
  }
  $totales[]=$ini;
  $cantidadreservada=0;
  $fechaini=buscarfecha(substr($tiempo, 0, 10));
  $disponible=DB::table('app_reservas')->where('bodega',$bodega )
  ->whereIn('estado',[1, 2, 3, 4, 5])
  ->Where(function ($query)
   {
     $tiempo=$_GET['tiempo'];
     $fechaini=buscarfecha(substr($tiempo, 0, 10));
     $fechafin=buscarfecha(substr($tiempo, 11, 10));
     $query->whereBetween('desde', [$fechaini, $fechafin])
     ->orwhereBetween('hasta', [$fechaini, $fechafin]);
   })->get();

  foreach ($disponible as $value) {
    $productosre=$value->producto;
    $coincidencia = strpos($productosre, $productoscod[$i]);
    if (!($coincidencia === false)) {
     $produc=explode(",", $value->producto);
     $cant=explode(",", $value->cantidad);
     $position=array_search($productoscod[$i], $produc);
     $cantidadreservada=$cantidadreservada+$cant[$position];
   }
  }
  $cantidadtotalreservada[]=$cantidadreservada;

  }
  $contador=count($productoscod);
  $cantidadrequerida=explode("-", $cantidad);
   for ($i=0; $i < $contador; $i++) { 
   $disp=$totales[$i]-$cantidadtotalreservada[$i]-$cantidadrequerida[$i];
   if ($disp>=0) {    
   }else{
    if ($caso==0) {
      # code...
    }else{
$caso=2;      
    }
  break;
   }
 }
 if ($caso==2) {
    echo '<button type="submit" id="boton" class="disableda btn btn-danger" value="1" name="nuevosproductos">Agregar estos productos</button>';
  }elseif ($caso==1) {
    echo '<button type="submit" id="boton" class="disabledb btn btn-success" value="1" name="nuevosproductos" >Agregar estos productos</button>';
  }

  return View::make("show.confirmar")->with(array('productos'=>$productosfinal,'reservada'=>$cantidadtotalreservada,'total'=>$totales,'codigos'=>$codigosf,'iteracions'=>$contador));
  }
  public function ver($id)
  {

      $valores=DB::table('app_facturaciones')->where('id_reserva',$id)->orderby('id','desc')->take(1)->get();
      $reserva=DB::table('app_reservas')->where('id',$id)->get();
      foreach ($reserva as $value) {
    $bodega=$value->bodega;
  }
if (Auth::user()->id_perfil==1) {
  validaradministrador(2);
}else{
if (Auth::user()->bodega_id!=$bodega) {
  validaradministrador(1);
}
}
      return View::make("pedidos.pedido")->with(array('reserva'=>$reserva,'valores'=>$valores));
  }

function registrarabono(Request $request)
{
 if (validarcertificado()) {
    }
    else{
            Session::flash('error_message', 'Para registrar un abono debes remplazar http por https en el link' );
      return back();

    } 
          validaradministrador(2);
  if ($request->has('abono')) {
    $this->validate($request, ['Abono' => 'required',]);
  }
  $id=$request->id;
  $saldo=DB::table('app_facturaciones')->where('id_reserva',$id)->orderby('id','desc')->take(1)->value('total_restante');
  
$abono=str_replace('.','',$request->Abono);


  if ($abono > $saldo) {
  Session::flash('error_message',' El abono no puede superar los $ '.number_format($saldo).' pesos' );
  return back();
}else {
$facturas=DB::table('app_facturaciones')->where('id_reserva',$id)->orderby('id','desc')->take(1)->get();
  foreach ($facturas as $value) {
$concepto_descuentos=$value->concepto_descuentos;
$descuentos=$value->descuentos;
$concepto_impuesto=$value->concepto_impuestos;
$impuesto=$value->impuestos;
$abonototal=$value->total_abonado;
$totalfacturado=$value->total_facturado;
$totalrestante=$value->total_restante;
}

$iva=$request->has('iva');
if ($iva) {
$iva=1;
}else{
  $iva=0;
}

$totaldesc=0;
$recargos=0;
if ($request->has('Concepto')) {  
  $this->validate($request, [
    'Concepto' => 'required',
    'descuento' => 'required',

  ]);
  $concepto_descuentos=implode(",", $request->Concepto).",".$concepto_descuentos;
  $descuentos=implode(",", $request->descuento).",".$descuentos;
  $descuentos=str_replace('.','',$descuentos);
}


if ($request->has('conceptoimpuesto')) {
  $concepto_impuesto=implode(",", $request->conceptoimpuesto).",".$concepto_impuesto;
  $impuesto=implode(",", $request->impuesto).",".$impuesto;
  $impuesto=str_replace('.','',$impuesto);
}

$impu=explode(',', $impuesto);
$imp=array_sum($impu);

$desc=explode(',', $descuentos);
$des=array_sum($desc);


if ($iva==1) {
  $iva=1;
  (float)$cobrariva=$totalfacturado*0.19;
  (float)$total_facturado=$totalfacturado+$cobrariva;
}else{
  $iva=0;
  (float)$total_facturado=$totalfacturado;
}

$abonototal=$abonototal+$abono;
(float)$totalrestante=$total_facturado-$abonototal-$des+$imp;


  DB::table('app_facturaciones')->insert([['id_reserva'=>$id,'concepto_descuentos'=>$concepto_descuentos,
  'descuentos'=>$descuentos,'concepto_impuestos'=>$concepto_impuesto,'impuestos'=>$impuesto,
  'fecha_abono'=>Carbon::now(),'total_abonado'=>$abonototal,'abono'=>$abono,'total_facturado'=>$totalfacturado,'total_restante'=>$totalrestante,'iva'=>$iva]]);
 
 $descripcion='id_reserva = '.$id.', concepto_descuentos = '.$concepto_descuentos.', descuentos = '.$descuentos.',concepto_impuestos = '.$concepto_impuesto.', impuestos = '.$impuesto.', fecha_abono = '.Carbon::now().', total_abonado = '.$abonototal.', abono = '.$abono.', total_facturado = '.$totalfacturado.', total_restante = '.$totalrestante;
   DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>'0','clase'=>7,'caso'=>2,'identificador'=>$id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);


  Session::flash('flash_message', ' Transaccion Registrada' );
  return back();
}
}
public function editar($id)
{
  https();
  $reserva=DB::table('app_reservas')->where('id',$id)->get();
  foreach ($reserva as $value) {
    $bodega=$value->bodega;
  }
if (Auth::user()->id_perfil==1) {
  validaradministrador(2);
}else{
if (Auth::user()->bodega_id!=$bodega) {
  validaradministrador(1);
}
}

  $productos=DB::table('app_productos')->select('codigo','nombre')->get();
  $clientes=DB::table('app_clientes')->select('id','nombre','apellido')->get();
  $bodegas=DB::table('app_bodegas')->select('id','nombre')->get();
  return View::make("pedidos.editar")->with(array('reserva'=>$reserva,'productos'=>$productos,'clientes'=>$clientes,'bodegas'=>$bodegas));
}
public function eliminarproducto($ordenf , $producto, $ubicacion)
{
  https();
  validaradministrador(2);
  $reserva=DB::table('app_reservas')->where('id',$ordenf)->select('producto','cantidad','cliente')->get();
  foreach ($reserva as $orden) {
    $productos=$orden->producto;
    $cantidades=$orden->cantidad;
   $cliente=$orden->cliente;
}

$productos=explode(",",$productos);
$cantidades=explode(",",$cantidades);
$count=count($productos);
$posicion=array_search($producto,$productos);
unset($cantidades[$posicion]);
unset($productos[$posicion]);
$productos=array_filter($productos, "strlen");
$cantidades=array_filter($cantidades, "strlen");

$productos=implode(",",$productos);
$cantidades=implode(",",$cantidades);



$totalfacturado=valorfactura($productos,$cantidades,$cliente);
$facturacion=DB::table('app_facturaciones')->where('id_reserva',$ordenf)->take(1)->orderby('id','desc')->get();
foreach ($facturacion as $value) {
  $totalanterior=$value->total_facturado;
  $restanteanterior=$value->total_restante;
  $concepto_descuentos=$value->concepto_descuentos;
  $descuentos=$value->descuentos;
  $concepto_impuesto=$value->concepto_impuestos;
  $impuesto=$value->impuestos;
  $fecha_abono=$value->fecha_abono;
  $abonototal=$value->total_abonado;
  $iva=$value->iva;
}

$impu=explode(',', $impuesto);
$imp=array_sum($impu);

$desc=explode(',', $descuentos);
$des=array_sum($desc);


if ($iva==1) {
  $iva=1;
  (float)$cobrariva=$totalfacturado*0.19;
  (float)$total_facturado=$totalfacturado+$cobrariva;
}else{
  $iva=0;
  (float)$total_facturado=$totalfacturado;
}


(float)$totalrestante=$total_facturado-$abonototal-$des+$imp;



  DB::table('app_facturaciones')->insert([['id_reserva'=>$ordenf,'concepto_descuentos'=>$concepto_descuentos,
  'descuentos'=>$descuentos,'concepto_impuestos'=>$concepto_impuesto,'impuestos'=>$impuesto,
  'fecha_abono'=>$fecha_abono,'total_abonado'=>$abonototal,'total_facturado'=>$totalfacturado,'total_restante'=>$totalrestante,'iva'=>$iva]]);


$reserva=DB::table('app_reservas')->where('id',$ordenf)->update(['producto'=>$productos,'cantidad'=>$cantidades]);

$descripcion='productos = '.$productos.', cantidad = '.$cantidades;

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,'clase'=>7,'caso'=>2,'identificador'=>$ordenf,'descripcion'=>$descripcion,'ubicacion'=>str_replace("a", ",", $ubicacion),'fecha'=>Carbon::now()]]);

return View::make("pedidos.eliminar")->with(array('orden'=>$ordenf,'productos'=> $productos,'cantidades'=>$cantidades));
}

public function editarproducto($ordenf , $producto, $cantidad, $ubicacion)
{
    https();
    validaradministrador(2);

  $reserva=DB::table('app_reservas')->where('id',$ordenf)->select('producto','cantidad','cliente')->get();
  foreach ($reserva as $orden) {
    $productos=$orden->producto;
    $cantidades=$orden->cantidad;
    $cliente=$orden->cliente;
}
$productos=explode(",",$productos);
$cantidades=explode(",",$cantidades);
$count=count($productos);
$posicion=array_search($producto,$productos);
$cantidades[$posicion]=$cantidad;
$productos=implode(",",$productos);
$cantidades=implode(",",$cantidades);

$totalfacturado=valorfactura($productos,$cantidades,$cliente);
$facturacion=DB::table('app_facturaciones')->where('id_reserva',$ordenf)->take(1)->orderby('id','desc')->get();
foreach ($facturacion as $value) {
  $totalanterior=$value->total_facturado;
  $restanteanterior=$value->total_restante;
  $concepto_descuentos=$value->concepto_descuentos;
  $descuentos=$value->descuentos;
  $concepto_impuesto=$value->concepto_impuestos;
  $impuesto=$value->impuestos;
  $fecha_abono=$value->fecha_abono;
  $abonototal=$value->total_abonado;
  $iva=$value->iva;
}

$impu=explode(',', $impuesto);
$imp=array_sum($impu);

$desc=explode(',', $descuentos);
$des=array_sum($desc);


if ($iva==1) {
  $iva=1;
  (float)$cobrariva=$totalfacturado*0.19;
  (float)$total_facturado=$totalfacturado+$cobrariva;
}else{
  $iva=0;
  (float)$total_facturado=$totalfacturado;
}


(float)$totalrestante=$total_facturado-$abonototal-$des+$imp;

  DB::table('app_facturaciones')->insert([['id_reserva'=>$ordenf,'concepto_descuentos'=>$concepto_descuentos,
  'descuentos'=>$descuentos,'concepto_impuestos'=>$concepto_impuesto,'impuestos'=>$impuesto,
  'fecha_abono'=>$fecha_abono,'total_abonado'=>$abonototal,'total_facturado'=>$totalfacturado,'total_restante'=>$totalrestante,'iva'=>$iva]]);

$reserva=DB::table('app_reservas')->where('id',$ordenf)->update(['producto'=>$productos,'cantidad'=>$cantidades]);
  $descripcion='productos = '.$productos.', cantidad = '.$cantidades;

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,'clase'=>7,'caso'=>2,'identificador'=>$ordenf,'descripcion'=>$descripcion,'ubicacion'=>str_replace("a", ",", $ubicacion),'fecha'=>Carbon::now()]]);
return View::make("pedidos.eliminar")->with(array('orden'=>$ordenf,'productos'=> $productos,'cantidades'=>$cantidades));
}

public function confirmarproducto()
{
    validaradministrador(2);

    $producto=$_GET['productid'];
    $id=$_GET['id'];
    $cantidad=$_GET['cantidad'];
    $bodega=$_GET['bodega'];
    $fechaini=$_GET['desde'];
    $fechaini=str_replace("/", " ", $fechaini);
    $fechafin=$_GET['hasta'];
    $fechafin=str_replace("/", " ", $fechafin);

    $ini=0;
    $total=DB::table('app_movimientos')->where('producto_id', $producto)->where('bodega',$bodega )->take(1)->orderby('creado','desc')->get();
    $productosf=DB::table('app_productos')->where('codigo', $producto)->take(1)->get();
    foreach ($productosf as $value) {
      $productosfinal=$value->nombre;
      $codigosf=$value->codigo;
    }
    foreach ($total as $value) {
      $ini=$value->total;
    }

    $totales=$ini;
    $cantidadreservada=0;
    $disponible=DB::table('app_reservas')->whereBetween('desde', [$fechaini, $fechafin])->orwhereBetween('hasta', [$fechaini, $fechafin])->get();
    foreach ($disponible as $value) {
      $productosre=$value->producto;
      $coincidencia = strpos($productosre, $producto);
      if (!($coincidencia === false)) {
        $produc=explode(",", $value->producto);
        $cant=explode(",", $value->cantidad);
        $position=array_search($producto, $produc);
        $cantidadreservada=$cantidadreservada+$cant[$position];
     }
    }
    $disponibles=$ini-$cantidadreservada;
    if ($cantidad>$disponibles) {
    echo "<p style='color:red'>".$disponibles ." disponibles para alquiler en las fechas seleccionadas<p>";
    echo "<script type='text/javascript'> 
     $('#aeditar".$id."').addClass('disableda');
      $('#aeditar".$id."').removeClass('disabledb');
    </script>";
    }else {
    $disp=$totales-$cantidadreservada;
    echo $cantidadreservada." productos reservados de ".$totales." , disponibles ". $disp;
     echo "<script type='text/javascript'> 
     $('#aeditar".$id."').addClass('disabledb');
      $('#aeditar".$id."').removeClass('disableda');
    </script>";
   }
  
}
public function confirmarcambiofecha()
{
    validaradministrador(2);

    $orden=$_GET['orden'];
    $bodega=$_GET['bodega'];
    $tiempo=$_GET['fecha'];
    $caso=$_GET['caso'];
    $fechaini=buscarfecha2(substr($tiempo, 0, 17));
    $fechafin=buscarfecha2(substr($tiempo, 18, 17));
    $buscarproductos=DB::table('app_reservas')->where('id', $orden)->select('producto','cantidad','desde','hasta')->take(1)->get();
    foreach ($buscarproductos as $value) {
      $productosorden=$value->producto;
      $cantidadesorde=$value->cantidad;
      $desde=$value->desde;
      $hasta=$value->hasta;
    }

  if (empty($tiempo)) {
    return '
    <div class="alert alert-danger alert-dismissable fade in">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Lo sentimos! </strong> Debes seleccionar un rango de tiempo
    </div>';
  }
  $productoscod=explode(",",$productosorden);
  for ($i=0; $i < count($productoscod) ; $i++) {
  $ini=0;
  $total=DB::table('app_movimientos')->where('producto_id', $productoscod[$i])->where('bodega',$bodega )->take(1)->orderby('creado','desc')->get();
  $productosf=DB::table('app_productos')->where('codigo', $productoscod[$i])->take(1)->get();
  foreach ($productosf as $value) {
    $productosfinal[]=$value->nombre;
    $codigosf[]=$value->codigo;
  }
  foreach ($total as $value) {
    $ini=$value->total;
  }
  $totales[]=$ini;
  $cantidadreservada=0;
  $disponible=DB::table('app_reservas')->where('bodega',$bodega)
  ->whereIn('estado',[1, 2, 3, 4, 5])
  ->Where(function ($query)
   {
     $tiempo=$_GET['fecha'];
    $fechaini=buscarfecha2(substr($tiempo, 0, 17));
    $fechafin=buscarfecha2(substr($tiempo, 18, 17));
     $query->whereBetween('desde', [$fechaini, $fechafin])
     ->orwhereBetween('hasta', [$fechaini, $fechafin]);
   })->get();

  foreach ($disponible as $value) {
    $productosre=$value->producto;
    $coincidencia = strpos($productosre, $productoscod[$i]);
    if (!($coincidencia === false)) {
     $produc=explode(",", $value->producto);
     $cant=explode(",", $value->cantidad);
     $position=array_search($productoscod[$i], $produc);
     $cantidadreservada=$cantidadreservada+$cant[$position];
   }
  }
  $cantidadtotalreservada[]=$cantidadreservada;

  }
  $contador=count($productoscod);


$first = Carbon::parse($desde);
$second = Carbon::parse($hasta);
$a=Carbon::parse($fechaini)->between($first, $second);
$b=Carbon::parse($fechafin)->between($first, $second);
$c=$first->between(Carbon::parse($fechaini), Carbon::parse($fechafin));
$d=$second->between(Carbon::parse($fechaini), Carbon::parse($fechafin));
if ($a or $b or $c or $d) {
 for ($i=0; $i < $contador; $i++) { 
   $disp=$totales[$i]-$cantidadtotalreservada[$i];
   if ($disp>=1) {    
   }else{
$caso=2;
  break;
   }
 }
  
}else{

 for ($i=0; $i < $contador; $i++) { 
   $disp=$totales[$i]-$cantidadtotalreservada[$i]-$cantidadesorde[$i];
   if ($disp>=1) {    
   }else{
$caso=2;
  break;
   }
 }
}

  if ($caso==2) {
    echo '<button type="submit" id="boton" class="disableda btn btn-danger">Actualizar</button>';
  }else {
    echo '<button type="submit" id="boton" class="disabledb btn btn-success">Actualizar</button>';
  }
  return View::make("show.confirmar")->with(array('productos'=>$productosfinal,'reservada'=>$cantidadtotalreservada,'total'=>$totales,'codigos'=>$codigosf,'iteracions'=>$contador));

  }
  public function buscarproducto()
  {
    $codigo=$_GET['producto'];
    $bodega=$_GET['bodega'];
    $tiempo=$_GET['fecha'];
    $fechaini=buscarfecha2(substr($tiempo, 0, 17));
    $fechafin=buscarfecha2(substr($tiempo, 18, 17));
    $disponible=DB::table('app_reservas')->where('bodega',$bodega)
  ->whereIn('estado',[1, 2, 3, 4, 5])
  ->Where(function ($query)
   {
     $tiempo=$_GET['fecha'];
    $fechaini=buscarfecha2(substr($tiempo, 0, 17));
    $fechafin=buscarfecha2(substr($tiempo, 18, 17));
     $query->whereBetween('desde', [$fechaini, $fechafin])
     ->orwhereBetween('hasta', [$fechaini, $fechafin]);
   })->get();
  
  $cantidades=array();
  $clientes=array();
  $recepeciones=array();

  foreach ($disponible as $value) {
  $productosenreserva=$value->producto;
  $cantidadesreneservada=$value->cantidad;
  $tiene=strpos($productosenreserva,$codigo);
  if ($tiene !== false)  {
    $productos=explode(",",$productosenreserva);
    $cantidadesr=explode(",",$cantidadesreneservada);
    $count=count($productos);
    $posicion=array_search($codigo,$productos);
    $clientes[]=$value->cliente;
    $recepeciones[]=$value->recepcion;
    $desde[]=$value->desde;
    $hasta[]=$value->hasta;
    $cantidades[]=$cantidadesr[$posicion];
    $evento[]=$value->fecha_evento;
  }  
  }
if (empty($cantidades)) {
return "no existe reserva de este producto para las fechas seleccionadas";
}
$contador=count($cantidades);
  return View::make("show.detalleproducto")->with(array('producto'=>$codigo,'cantidades'=>$cantidades,'cliente'=>$clientes,'iteraciones'=>$contador,'desde'=>$desde,'hasta'=>$hasta,'recepeciones'=>$recepeciones,'evento'=>$evento));

  }
  public function actualizarorden(Request $request)
  {

  validaradministrador(2);
  $id=$request->id;
  if ($request->has('nuevosproductos')) {
        $cantidad=$request->cantidad;
        $productos=$request->producto;
        $productos=implode(",", $productos);
        $cantidad=implode(",",$cantidad);
 $reserva=DB::table('app_reservas')->where('id',$id)->get();
foreach ($reserva as $value) {
$productosanteriores=$value->producto;
$cantidadesanteriores=$value->cantidad;
$cliente=$value->cliente;
}
if (empty($productosanteriores)) {
$productosfinal=$productos;
$cantidadesfinal=$cantidad;
}else{
$productosfinal=$productosanteriores.','.$productos;
$cantidadesfinal=$cantidadesanteriores.','.$cantidad;
}
$totalfacturado=valorfactura($productosfinal,$cantidadesfinal,$cliente);

$facturacion=DB::table('app_facturaciones')->where('id_reserva',$id)->take(1)->orderby('id','desc')->get();
foreach ($facturacion as $value) {
  $totalanterior=$value->total_facturado;
  $restanteanterior=$value->total_restante;
  $concepto_descuentos=$value->concepto_descuentos;
  $descuentos=$value->descuentos;
  $concepto_impuesto=$value->concepto_impuestos;
  $impuesto=$value->impuestos;
  $fecha_abono=$value->fecha_abono;
  $abonototal=$value->total_abonado;
  $iva=$value->iva;
}

$impu=explode(',', $impuesto);
$imp=array_sum($impu);

$desc=explode(',', $descuentos);
$des=array_sum($desc);


if ($iva==1) {
  $iva=1;
  (float)$cobrariva=$totalfacturado*0.19;
  (float)$total_facturado=$totalfacturado+$cobrariva;
}else{
  $iva=0;
  (float)$total_facturado=$totalfacturado;
}

(float)$totalrestante=$total_facturado-$abonototal-$des+$imp;

  DB::table('app_facturaciones')->insert([['id_reserva'=>$id,'concepto_descuentos'=>$concepto_descuentos,
  'descuentos'=>$descuentos,'concepto_impuestos'=>$concepto_impuesto,'impuestos'=>$impuesto,
  'fecha_abono'=>$fecha_abono,'total_abonado'=>$abonototal,'total_facturado'=>$totalfacturado,'total_restante'=>$totalrestante,'iva'=>$iva]]);
  DB::table('app_reservas')->where('id',$id)->update(['producto'=>$productosfinal,'cantidad'=>$cantidadesfinal]);

  $descripcion='id_reserva = '.$id.', concepto_descuentos = '.$concepto_descuentos.', descuentos = '.$descuentos.', concepto_impuestos = '.$concepto_impuesto.', impuestos = '.$impuesto.', fecha_abono = '.$fecha_abono.', total_abonado = '.$abonototal.', total_facturado = '.$totalfacturado.', total_restante = '.$totalrestante.', iva = '.$iva;

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,'clase'=>7,'caso'=>2,'identificador'=>$id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);

  return back();

  }

  $bodega=$request->bodega_item;
  $direccion=$request->direccion;
  $cliente=$request->cliente;
  if ($request->has('editarfecha')) {
     $tiempo=$request->tiempo;
        $desde=buscarfecha4(substr($tiempo, 0, 19));
        $hasta=buscarfecha4(substr($tiempo, 22, 19));
        $evento=buscarfecha5($request->fecha_evento);
  }else{
    $desde=$request->reserva_desde;
    $hasta=$request->reserva_hasta;
    $evento=$request->reserva_evento;
  }

  $clienteantiguo=DB::table('app_reservas')->where('id',$id)->value('cliente');
  if ($cliente==$clienteantiguo) {


  }else{

$facturacion=DB::table('app_facturaciones')->where('id_reserva',$id)->take(1)->orderby('id','desc')->get();
foreach ($facturacion as $value) {
  $totalanterior=$value->total_facturado;
  $restanteanterior=$value->total_restante;
  $concepto_descuentos=$value->concepto_descuentos;
  $descuentos=$value->descuentos;
  $concepto_impuesto=$value->concepto_impuestos;
  $impuesto=$value->impuestos;
  $fecha_abono=$value->fecha_abono;
  $abonototal=$value->total_abonado;
  $iva=$value->iva;
}
$p=DB::table('app_reservas')->where('id',$id)->value('producto');
$c=DB::table('app_reservas')->where('id',$id)->value('cantidad');
$totalfacturado=valorfactura($p,$c,$cliente);

$impu=explode(',', $impuesto);
$imp=array_sum($impu);

$desc=explode(',', $descuentos);
$des=array_sum($desc);


if ($iva==1) {
  $iva=1;
  (float)$cobrariva=$totalfacturado*0.19;
  (float)$total_facturado=$totalfacturado+$cobrariva;
}else{
  $iva=0;
  (float)$total_facturado=$totalfacturado;
}


(float)$totalrestante=$total_facturado-$abonototal-$des+$imp;



DB::table('app_facturaciones')->insert([['id_reserva'=>$id,'concepto_descuentos'=>$concepto_descuentos,
  'descuentos'=>$descuentos,'concepto_impuestos'=>$concepto_impuesto,'impuestos'=>$impuesto,
  'fecha_abono'=>$fecha_abono,'total_abonado'=>$abonototal,'total_facturado'=>$totalfacturado,'total_restante'=>$totalrestante,'iva'=>$iva]]);

  }

  DB::table('app_reservas')->where('id',$id)->update(['desde'=>$desde,'hasta'=>$hasta,'bodega'=>$bodega,'cliente'=>$cliente,'recepcion'=>$direccion,'fecha_evento'=>$evento]);

  $descripcion='desde ='.$desde.', hasta = '.$hasta.'bodega = '.$bodega.', cliente = '.$cliente.', recepcion = '.$direccion.', fecha_evento = '.$evento;

  DB::table('app_seguimiento')->insert([['usuario'=>Auth::user()->id,'nombre'=>Auth::user()->name,
  'bodega'=>$bodega,'clase'=>7,'caso'=>2,'identificador'=>$id,'descripcion'=>$descripcion,'ubicacion'=>$request->posicion,'fecha'=>Carbon::now()]]);


  
return back();
 

 }
  

}
