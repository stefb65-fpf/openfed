<!DOCTYPE html>
<html prefix="og: https://ogp.me/ns#" lang="fr-FR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:site_name" content="ANCRE"/>
@yield('seo')
<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
{{--    <link rel="stylesheet" href='https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css'>--}}
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet">
{{--    <link rel="icon" type="image/png" href="{{ url('storage/app/public/favicon.ico') }}" />--}}
{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />--}}

    <title>Concours Open Fed de la Fédération Photographique de France</title>
    @yield('css')
    @yield('headjs')
</head>
<body>
<div class="container-fluid">
    @include('layouts.header')
</div>
<div class="container" >
    @include('layouts.flash')
    @yield('content')
</div>
<footer>
    @include('layouts.footer')
</footer>
@include('layouts.modal')
@yield('modal')

@if($cookie_rgpd == 0)
    @include('layouts.cookies')
@endif

<span id="route_for_acceptation_cookies" style="display: none">{{ route('ajax.acceptationCookies') }}</span>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{--<script src="{{ asset('js/jqueryui.js') }}"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>--}}
{{--<script src="/fancybox/jquery.fancybox-1.3.4.pack.js"></script>--}}

@yield('js')
<script src="{{ asset('js/frontend.js') }}"></script>
</body>
</html>
