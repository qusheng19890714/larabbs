<!DOCTYPE html>
{{-- getLocal是获取config默认设置的local值 --}}
<html lang="{{app()->getLocale()}}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'LaraBBS') - {{ setting('site_name', 'Laravel 进阶教程') }}</title>
        <meta name="description" content="@yield('description', 'LaraBBS 爱好者社区')" />
        <meta name="keyword" content="@yield('keyword', setting('seo_keyword', 'LaraBBS,社区,论坛,开发者论坛'))" />
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @yield('styles')
    </head>

    <body>
        <div id="app" class="{{ route_class() }}-page">

            @include('layouts._header')

            <div class="container">
                @include('layouts._message')
                @yield('content')

            </div>

            @include('layouts._footer')
        </div>

        @if (app()->isLocal())
        @include('sudosu::user-selector')
        @endif

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://js.pusher.com/4.0/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('b09f17649acf2087ef4d', {
            cluster: 'ap1',
            encrypted: true
        });

        var channel = pusher.subscribe('test');
        channel.bind('my-event', function(data) {
            alert(data.info);
        });
    </script>
    @yield('scripts')
    </body>
</html>