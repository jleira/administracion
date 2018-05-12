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
 <div class="col-lg-12">
       <div class="box box-primary">
				 @foreach ($categorias as $categoria)

          <div class="box-header with-border">
            <h3 class="box-title">Categoria {{$categoria->valor_lista}}</h3>
          </div>
                <!-- /.box-header -->
                <!-- form start -->

          <div class="box-body">
            <div class="form-group">
              <label for="exampleInput">Nombre</label>
								<p>{{$categoria->valor_item}}</p>
            </div>
						<div class="form-group">
							<label for="exampleInput">Descripcion</label>
							<p>{{$categoria->descripcion}}</p>
						</div>

						<div class="form-group">
							<label for="exampleInput">Codigo</label>
							<p for="">{{$categoria->valor_lista}}</p>
						</div>


							  </div>
								<a href="{{ url('editarcategoria/'.$categoria->valor_lista)}}">editar</a>
								@endforeach
            </div>
</div>



</div>

@endsection
