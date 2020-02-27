<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administrator</title>
    <script src="{{ asset('js/app.js?'.time()) }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Kanit:300,300i,400,400i,500,500i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link href="{{ asset('css/app.css?'.time()) }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css?'.time()) }}" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <table width="100%">
            <tr style="vertical-align: top;">
                <td width="12%">
                    <i class="fab fa-angrycreative fa-5x px-3"></i>
                    <ul class="sbar">
                        <li><a href="#">Groups</a></li>
                        <li class="actived"><a href="#">Blogs</a></li>
                        <li><a href="#">Pages</a></li>
                    </ul>
                </td>
                <td>
                    <div id="app">
                        <blogs-create></blogs-create>
                    </div>
                    {{-- <div id="content">
                        @yield('content')
                    </div> --}}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>