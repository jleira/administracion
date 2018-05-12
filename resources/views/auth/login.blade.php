
@extends('layouts.auth')

@section('htmlheader_title')
    Log in
@endsection


@section('content')
@if(Session::has('flash_message'))
<div class="alert alert-success alert-dismissable fade in">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Grandioso!</strong> {{Session::get('flash_message')}}
</div>
@endif
<body class="hold-transition login-page" style="background-color:white;padding-top:0px">
    <div class="login-box">
        <div class="login-logo" style="padding: 0px;margin: 0px;">
            <a href="{{ url('/home') }}"><img src="{{logo()}}" alt=""></a>
        </div><!-- /.login-logo -->

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> {{ trans('adminlte_lang::message.someproblems') }}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="login-box-body" style="background-color:rgba(255,255,255,0.2);border: solid 1px white;border-radius: 30px;">
    <p class="login-box-msg"> {{ trans('adminlte_lang::message.siginsession') }} </p>
    <form action="{{ url('/login') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="{{ trans('adminlte_lang::message.email') }}" name="email" style="background-color: transparent !important;border-radius:10px;"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ trans('adminlte_lang::message.password') }}" name="password" style="background-color: transparent !important;border-radius:10px;"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember"> {{ trans('adminlte_lang::message.remember') }}
                    </label>
                </div>
            </div><!-- /.col -->
            <div class="col-xs-12" >
                <button width="100% "type="submit" class="btn btn-primary btn-block btn-flat"
                style="border-radius: 10px">{{ trans('adminlte_lang::message.buttonsign') }}</button>
            </div><!-- /.col -->
        </div>
    </form>



</div><!-- /.login-box-body -->

</div><!-- /.login-box -->

    @include('layouts.partials.scripts_auth')

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>

@endsection
