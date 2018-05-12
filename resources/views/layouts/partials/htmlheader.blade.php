<head>
    <meta charset="UTF-8">
    <title>{{nombreempresa()}} </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="{{url('storage/app/public/img/favicon.png')}}" type="image/x-icon">

   <link href="{{ asset('/public/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
   <link href="{{ asset('/public/themes/explorer/theme.css')}}" rel="stylesheet">

    <link href="{{ asset('/public/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <!-- Theme style -->
    <link href="{{ asset('/public/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="{{ asset('/public/css/skins/skin-red.css') }}" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ asset('/public/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('/public/plugins/select2/select2.min.css')}}">

    <link rel="stylesheet" href="{{ asset('public/plugins/datatables/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" href="{{ asset('public/plugins/daterangepicker/daterangepicker-bs3.css')}}">
    <link href="{{ asset('/public/plugins/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('public/plugins/bootstrap-datetimepicker/src/sass/_bootstrap-datetimepicker.scss')}}" type="text/css">

    <script src="{{ asset('/public/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('/public/js/bootstrap.min.js') }}" type="text/javascript"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
{{colorprincipal()}}
