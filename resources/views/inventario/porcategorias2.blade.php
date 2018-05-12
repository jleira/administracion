 @extends('layouts.app')

 @section('htmlheader_title')
 {{ trans('adminlte_lang::message.home') }}
 @endsection


 @section('main-content')

 <div class="row">
  @if(Session::has('flash_message'))
  <div class="alert alert-success alert-dismissable fade in">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Grandioso!</strong> {{Session::get('flash_message')}}
  </div>
  @endif

  @foreach($bodegas as $bodega)
  <div class="col-lg-12">

    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">{{validarbodega($bodega)}} - {{formatofecha($fecha_inicio)}} / {{formatofecha($fecha_fin)}}</h3>
        <form id="form1" name="form1" role="form" enctype="multipart/form-data" method="POST" action="{{ url('imprimir') }}">
          {{ csrf_field() }}

          <input type="hidden" name="categoria" value="{{$cat}}">
          <input type="hidden" name="bodega" value="{{$bodega}}">
          <input type="hidden" name="fecha_inicio" value="{{$fecha_inicio}}">
          <input type="hidden" name="fecha_fin" value="{{$fecha_fin}}">

          <button type="submit" class="btn btn-danger pull-right" name="button" value="2" formtarget="_blank">Descargar(PDF)</button>
          <button type="submit" class="btn btn-success pull-right" name="button" value="5" formtarget="_blank">Descargar(EXCEL)</button>
        </form>
      </div>
      @foreach($categorias as $categoria)
      <div class="box-header">
        <h3 class="box-title">{{validarlista($categoria->valor_lista,4)}}</h3>

      </div>

      <!-- /.box-header -->
      <div class="box-body">
        <table id="productos" class=" productos table table-bordered table-striped">
          <thead>
            <tr>
              <th>Codigo</th>
              <th>Nombre</th>
              <th>Cantidad</th>
              <th>Reservados</th>
              <th>disponibles</th>
              <th>valor de compra</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productos as $producto)
            @if($producto->categoria==$categoria->valor_lista)
            <tr>
             <td>{{$producto->codigo}}</td>
             <td>{{$producto->nombre}}</td>
             @php
             $cantidad=totalp($producto->codigo,$bodega);
             $reservados=cant_reservada($producto->codigo,$bodega,$fecha_inicio,$fecha_fin);                        
             @endphp
             <td>{{$cantidad}}</td>
             <td>{{ $reservados}}</td>
             @if($cantidad-$reservados < 0)
             <td style="background-color: red" >{{$cantidad-$reservados}}</td>
             @else
             <td >{{$cantidad-$reservados}}</td>
             @endif
             <td>{{formatoprecio($producto->vl_unitario)}}</td>
             <td>{{formatoprecio($cantidad*$producto->vl_unitario)}}</td>
           </tr>
           @endif
           @endforeach
         </tbody>

       </table>

     </div>
     @endforeach
   </div>
 </div>
 @endforeach

</div>
<script type="text/javascript">
  $(function () {
   $(".productos").DataTable({
    "language": {
     "lengthMenu": "Mostrar _MENU_ productos por pagina",
     "zeroRecords": "No hay productos registrados",
     "info": "Pagina _PAGE_ de _PAGES_",
     "infoEmpty": "",
     "infoFiltered": "(Filtrado de _MAX_ bodegas )",
     "search":'',
        "searchPlaceholder": "Buscar",
     "paginate": {
       "first":      "Primero",
       "last":       "Ultimo",
       "next":       "Siguiente",
       "previous":   "Anterior"
     }
   }
 });
 });

</script>
@endsection
