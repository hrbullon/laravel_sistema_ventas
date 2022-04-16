<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/simple-line-icons.min.css" rel="stylesheet">
        <!-- Main styles for this application -->
        <link href="css/style.css" rel="stylesheet">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
        @inertiaHead
    </head>
    <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
        @inertia
        
        @env ('local')
            <script src="http://localhost:8080/js/bundle.js"></script>
        @endenv

        <!-- Bootstrap and necessary plugins -->
        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/pace.min.js"></script>
        <!-- Plugins and scripts required by all views -->
        <script src="js/Chart.min.js"></script>
        <!-- GenesisUI main scripts -->
        <script src="js/template.js"></script>
    </body>
</html>
