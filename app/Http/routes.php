<?php
Route::auth();
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');


Route::get('/registrarcliente', 'ClientesController@registrarcliente');
Route::post('registrarcliente', 'ClientesController@crearcliente');
route::get('clientes','ClientesController@buscarcliente');
Route::get('editarcliente/{id}', 'ClientesController@editarcliente');
Route::post('/editarcliente', 'ClientesController@actualizarcliente');



Route::get('registrarproducto', 'ProductosController@registrarproducto');
Route::post('registrarproducto', 'ProductosController@crearproducto');
Route::get('codigo/producto/{categoria}', 'ProductosController@codigodisponible');
Route::get('buscarproductos', 'ProductosController@productos');
Route::get('producto/{id}', 'ProductosController@productoid');
Route::get('/editarproductos', 'ProductosController@editarvista');
Route::get('editarproducto/{id}', 'ProductosController@editarproducto');
Route::post('editarproducto', 'ProductosController@editarproductointerno');
Route::get('productos/eliminarimagen/{codigo}/{imagen}/{ubicacion}', 'ProductosController@eliminarimagen');
Route::get('historicoproducto', 'ProductosController@historico');



Route::get('registarcategoria', 'CategoriasController@registrarcategoria');
Route::post('registrarcategoria', 'CategoriasController@crear');
Route::get('editarcategoria/{id}', 'CategoriasController@editar');
Route::get('editarcategoria', 'CategoriasController@editarc');
Route::post('editarcategoria', 'CategoriasController@editarcategoria');
Route::get('categoria/{id}', 'CategoriasController@categoria');
Route::get('categorias', 'CategoriasController@buscar');




Route::post('/registerstorage', 'BodegasController@registrarbodega');
Route::get('/registarbodega', 'BodegasController@registrar');
Route::get('/editarbodega', 'BodegasController@editarbodega');
Route::get('/editarbodega/{id}', 'BodegasController@actualizarbodega');
Route::post('/editarstorage', 'BodegasController@editar');


Route::get('inventario', 'InventariosController@inventarioform');
Route::post('buscarinventario', 'InventariosController@inventario');
Route::post('imprimir', 'InventariosController@imprimir');
Route::get('inventario2', 'InventariosController@imprimir2');

Route::get('buscarpedido', 'PedidosController@index');
Route::get('registrarpedido', 'PedidosController@registrarpedido');
Route::post('registrarpedido', 'PedidosController@crearpedido');
Route::get('confirmar', 'PedidosController@confirmar');
Route::get('orden/{id}', 'PedidosController@ver');
Route::post('generarguia', 'PedidosController@generarguia');
Route::post('actualizarorden', 'PedidosController@registrarabono');
Route::get('editarreserva/{id}', 'PedidosController@editar');
Route::get('editarpedido/eliminarproducto/{pedido}/{producto}/{ubicacion}', 'PedidosController@eliminarproducto');
Route::get('confirmarproducto', 'PedidosController@confirmarproducto');
Route::get('confirmarcambiofecha', 'PedidosController@confirmarcambiofecha');
Route::get('buscarproductoenreservas', 'PedidosController@buscarproducto');
Route::post('editarorden', 'PedidosController@actualizarorden');
Route::get('editarpedido/editarproducto/{pedido}/{producto}/{cantidad}/{ubicacion}', 'PedidosController@editarproducto');
Route::get('rangoreservas','PedidosController@rangoreservas');

Route::get('confirmar1', 'ViewController@confirmar1');
Route::get('/verdireccion', 'ViewController@direccioncliente');


Route::get('registrarmovimiento', 'ViewController@entradaproducto');
Route::get('movimiento', 'ViewController@movimientoid');
Route::post('registrarmovimiento', 'StorageController@registromovimiento');
Route::get('buscarmovimiento', 'ViewController@movimientos');


Route::post('/registraritem', 'StorageController@saveitem');
Route::get('/buscarvaloritem', 'ViewController@buscarvaloritem');


Route::get('usuarios', 'AdminController@usuarios');
Route::get('registrarusuario', 'AdminController@registrarusuario');
Route::post('registrarusuario', 'AdminController@registrarusuariof');
Route::post('editarusuario', 'AdminController@editar');
Route::get('admin/editarusuario/{id}', 'AdminController@editarusuario');


Route::post('cambiarestado', 'EstadosController@cambiarestado');
Route::get('administrador/cambiarestado', 'EstadosController@actualizarestados');


Route::get('/cambiarparametrosgenerales', 'GeneralesController@index');
Route::post('cambiargenerales', 'GeneralesController@actualizar');
Route::get('/cambiarpass/{pass}', 'GeneralesController@cambiarpass');

Route::get('/seguimientos', 'SeguimientosController@index');
Route::get('seguimientos/{clase}/{identificador}', 'SeguimientosController@seguimientos');

