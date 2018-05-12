<div class="">
  <label for="">Descripcion:</label>
  <p>{{$descripcion}}</p>
</div>
<div class="table-responsive">
  <table class="table">
    <thead>
        <tr>
          <th>Productos</th>
          <th>Cantidad</th>
          <th>Movimiento</th>
          <th>Bodega</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
@foreach($movimientos as $movimiento)
<tr>
  <td>{{validarproductoporcodigo($movimiento->producto_id)." - ".$movimiento->producto_id }}</td>
  <td>{{$movimiento->cantidad}}</td>
    <td>{{validarlista($movimiento->item,1)}}</td>
      <td>{{validarbodega($movimiento->bodega)}}</td>
    <td>{{$movimiento->total}}</td>

</tr>
@endforeach
      </tbody>
  </table>
</div>
