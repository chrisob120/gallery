<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>{{$title or ''}}</title>

    <!-- Google web fonts -->
    {{ HTML::style('http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700') }}
    {{ HTML::style('http://fonts.googleapis.com/css?family=Cookie') }}

    {{ HTML::style('bootstrap/css/bootstrap.min.css') }}
    {{ HTML::style('css/screen.css') }}
    {{ HTML::style('fancybox/fancybox.css') }}

    @section('scripts')
        {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js') }}
        {{ HTML::script('//code.jquery.com/ui/1.10.4/jquery-ui.js') }}
    @show
</head>
<body>
    <div id="wrap">

        <div id="main">
            <div id="content" class="content-full">
                @yield('content')
            </div>
        </div>
    </div>

    @section('inlineScripts')
        {{ HTML::script('bootstrap/js/bootstrap.min.js') }}
        {{ HTML::script('fancybox/fancybox.pack.js') }}
        {{ HTML::script('js/jquery.knob.js') }}

        <!-- jQuery File Upload Dependencies -->
        {{ HTML::script('js/jquery.ui.widget.js') }}
        {{ HTML::script('js/jquery.iframe-transport.js') }}
        {{ HTML::script('js/jquery.fileupload.js') }}

        {{ HTML::script('js/common.js') }}

</body>
</html>