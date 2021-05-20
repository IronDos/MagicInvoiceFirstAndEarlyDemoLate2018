<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'MagicInvocie') }}</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link 
    rel="stylesheet"
    href="https://cdn.rtlcss.com/bootstrap/v4.0.0/css/bootstrap.min.css"
    integrity="sha384-P4uhUIGk/q1gaD/NdgkBIl3a6QywJjlsFJFk7SPRdruoGddvRVSwv5qFnvZ73cpz"
    crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>

    <!-- Font Awesome CDN-->
    <link rel="stylesheet"
    href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
    integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
    crossorigin="anonymous">

    <!-- VueJs CDN -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    
    <!-- Axios CDN -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    
    <!-- ChartsJs -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
    
    <!-- JS Files -->

    <!-- Flags -->

</head>
<body>
    @include('layouts.navbar')
    <main class="py-4">
        @yield('content')
    </main>
</body>
</html>