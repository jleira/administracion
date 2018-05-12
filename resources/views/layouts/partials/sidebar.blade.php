<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <section class="sidebar">

        <ul class="sidebar-menu">
        @if(Auth::guest())
            <li class="header">:)</li>
            <li class="active"><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>{{ trans('adminlte_lang::message.home') }}</span></a></li>
            @else
            @if(Auth::user()->id_perfil==1)
            <li class="header">{{validarlista(Auth::user()->id_perfil,2)}}</li>
            @else
            <li class="header">{{validarlista(Auth::user()->id_perfil,2)}} - {{validarbodega(Auth::user()->bodega_id)}}</li>
            @endif

            <!-- Optionally, you can add icons to the links -->
            <li class="{{active('home')}}"><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>{{ trans('adminlte_lang::message.home') }}</span></a></li>
            <li class="treeview {{active('cliente')}}">
                <a href="#"><i class='fa fa-users'></i> <span>CLIENTES</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{active('registrarcliente')}}"><a href="{{url('registrarcliente')}}"><i class="fa fa-user-plus"></i>REGISTAR</a></li>
                    <li class="{{active('clientes')}}"><a href="{{url('clientes')}}"><i class="fa fa-user-times"></i>EDITAR</a></li>
                </ul>
            </li>
            @if(Auth::user()->id_perfil==1)

            <li class="treeview {{active('bodega')}}">
                <a href="#"><i class='fa fa-industry'></i> <span>BODEGAS</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{active('registarbodega')}}"><a href="{{ url('registarbodega')}}">REGISTAR</a></li>
                    <li class="{{active('editarbodega')}}"><a href="{{ url('editarbodega')}}">EDITAR</a></li>
                </ul>
            </li>
            @endif
            <li class="treeview {{active('categoria')}}">
                <a href="#"><i class='fa fa-tags'></i> <span>CATEGORIAS</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{active('registarcategoria')}}"><a href="{{url('registarcategoria')}}">REGISTAR</a></li>
                    <li class="{{active('editarcategoria')}}"><a href="{{url('editarcategoria')}}">EDITAR</a></li>
                    <li class="{{active('categorias')}}"><a href="{{url('categorias')}}">BUSCAR</a></li>
                </ul>
            </li>
            
            <li class="treeview {{active('producto')}}">
                <a href="#"><i class='fa fa-cutlery'></i> <span>PRODUCTOS</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">

                    <li class="{{active('registrarproducto')}}"><a href="{{ url('registrarproducto')}}"><i class="fa fa-plus-square"></i>REGISTRAR</a></li>
                                @if(Auth::user()->id_perfil==1)
                    <li class="{{active('editarproductos')}}"><a href="{{url('editarproductos')}}"><i class="fa fa-eraser"></i>EDITAR</a></li>
                    @endif
                    <li class="{{active('editarproductos')}}"><a  href="{{url('buscarproductos')}}"><i class='fa  fa-search'></i> <span>BUSCAR</span></a></li>
                </ul>
            </li>
                        <li class="treeview {{active('pedido')}} {{active('orden')}}">
                <a href="#"><i class='fa fa-calendar-o'></i> <span>PEDIDOS</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{active('registrarpedido')}}"><a href="{{ url('registrarpedido')}}">REGISTAR</a></li>
                    <li class="{{active('buscarpedido')}}"><a href="{{ url('buscarpedido')}}">BUSCAR</a></li>
                </ul>
            </li>
            <li class="treeview {{active('inventario')}}">
                <a href="#"><i class='fa fa-line-chart'></i> <span>INVENTARIO</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{active('inventario')}}"><a href="{{ url('inventario')}}"><i class="fa fa-search"></i>Buscar</a></li>
                </ul>
            </li>
            <li class="treeview {{active('movimiento')}}">
                <a href="#"><i class='fa fa-exchange'></i> <span>MOVIMIENTOS</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{active('registrarmovimiento')}}"><a href="{{ url('registrarmovimiento')}}"><i class="fa fa-plus"></i>Registrar</a></li>
                    <li class="{{active('buscarmovimiento')}}"><a href="{{ url('buscarmovimiento')}}"><i class="fa fa-search"></i>Buscar</a></li>
                </ul>
            </li>


            @if(Auth::user()->id_perfil==1)

            <li class="treeview {{active('usuario')}}">
                <a href="#"><i class='fa fa-child'></i> <span>USUARIOS</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{active('usuarios')}}"><a  href="{{ url('usuarios')}}"><i class='fa  fa-users'></i> <span>Buscar</span></a></li>
                    <li class="{{active('registrarusuario')}}"><a  href="{{ url('registrarusuario')}}"><i class='fa fa-user-plus'></i><span>Registrar</span></a></li>
                </ul>
            </li>
            <li class="{{active('seguimiento')}}"><a href="{{ url('seguimientos')}}"><i class='fa fa-user-secret'></i> <span>SEGUIMIENTOS</span></a></li>
           
            @endif
            @endif
                         <li class="{{active('home')}}"><a href="{{ url('storage/app/public/guia/MANUAL_APLICATIVO.pdf')}}"><i class='fa fa-download'></i> <span>Descargar guia</span></a></li>

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
