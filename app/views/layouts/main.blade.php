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

    <script>var base_url = "<?= url(); ?>";</script>
</head>
<body>
    <div id="wrap">
        <header>
            @section('header')
            <div class="header-limit">
                <h1><a href="{{URL::to('/')}}">Gallery<span>site</span></a></h1>

                <nav>
                    <a class="fancybox upload-btn btn btn-primary" href="{{URL::to(Request::url())}}"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Upload</a>
                </nav>

                @if (Auth::guest())
                <ul>
                    <li><a class="fancyregister" href="{{URL::to('/register')}}">Register</a></li>
                    <li><a class="fancylogin signin" href="{{URL::to('/login')}}">Sign In</a></li>
                </ul>
                @else
                <div class="header-user-menu">
                    <img src="http://192.168.2.218/gallery/public/uploads/images/2015yrfjp1l1hp/clinbv.jpg" alt="Cat" />
                    <ul>
                        <li><a href="#">Images</a></li>
                        <li><a href="#">Albums</a></li>
                        <li><a href="{{URL::to('/profile/' .Auth::user()->username)}}">Profile</a></li>
                        <li><a href="#">Comments</a></li>
                        <li><a href="{{URL::to('/logout')}}" class="highlight">Log Out</a></li>
                    </ul>
                </div>
                @endif

            </div>
            @show
        </header>

        <div id="main">
            @section('contentChoice')
            <div id="content" class="content-block">
            @show

                @yield('content')

            </div>
        </div>
    </div>

    <!-- Fancy Box HTML -->
    <div id="loginBox" style="display: none;">
        <div class="fancy-wrap">
            {{ $loginView }}
        </div>
    </div>
    <div id="registerBox" style="display: none;">
        <div class="fancy-wrap">
            {{ $registerView }}
        </div>
    </div>
    <div id="uploadBox" style="display: none;">
        <div class="fancy-wrap">
            {{ $uploadView }}
        </div>
    </div>

    <!-- Error Box -->
    <div id="error-box" style="display: none;"></div>


    @section('inlineScripts')
    {{ HTML::script('bootstrap/js/bootstrap.min.js') }}
    {{ HTML::script('fancybox/fancybox.pack.js') }}
    {{ HTML::script('js/jquery.knob.js') }}

    <!-- jQuery File Upload Dependencies -->
    {{ HTML::script('js/jquery.ui.widget.js') }}
    {{ HTML::script('js/jquery.iframe-transport.js') }}
    {{ HTML::script('js/jquery.fileupload.js') }}

    {{ HTML::script('js/common.js') }}

    {{-- If the user is logged in, add the js for mobile drop down help --}}
    @if (Auth::check())

        <!-- Mobile Drop Down JS -->
        <script>
            $(document).ready(function(){
                var userMenu = $('.header-limit .header-user-menu');

                userMenu.on('touchend', function(e){
                    userMenu.show();

                    e.preventDefault();
                    e.stopPropagation();
                });

                // Makes the user dropdown work on mobile devices
                $(document).on('touchend', function(e){

                    // If the page is touched anywhere outside the user menu, close it
                    userMenu.hide();

                });

            });
        </script>
    @endif

    @show

</body>
</html>