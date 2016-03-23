<div id="register-box" class="inline box-hold reg-fix">
    <div class="logo">Logo</div>
    <h1>User Registration</h1>
    <div id="register-hold">
        {{ Form::open(array('action' => 'AccountController@login', 'name' => 'loginTest')) }}
        <p class="field" data-toggle="tooltip" title="">
            {{Form::text('username', '', array('placeholder'=>'Username', 'required'))}}
            <span class="glyphicon glyphicon-user"></span>
        </p>
        <p class="field" data-toggle="tooltip" title="">
            {{Form::email('email', '', array('placeholder'=>'Email', 'required'))}}
            <span class="glyphicon glyphicon-envelope"></span>
        </p>
        <p class="field" data-toggle="tooltip" title="">
            {{Form::password('password', array('placeholder'=>'Password', 'required'))}}
            <span class="glyphicon glyphicon-lock"></span>
        </p>
        <p class="field">
            {{Form::password('password_confirmation', array('placeholder' => 'Confirm Password', 'required', 'class' => 'last'))}}
            <span class="glyphicon glyphicon-lock"></span>
        </p>
        <p class="submit">
            <button type="submit" name="submit">
                <span class="glyphicon glyphicon-arrow-right"></span>
            </button>

        {{ Form::close() }}

        <p class="register-text small-text">Already have an account?  <a{{ (Request::is('register')) ? '' : ' class="fancylogin"' }} href="{{URL::to('/login')}}">Sign In</a></p>
    </div>
    <div class="bottom small-text">
        <div class="footer">Copywrite 2015 Blah Blah Gallery Stuff</div>
    </div>
</div>

<ul id="test">

</ul>

@if(Session::has('message'))
    <div id="msg-box">
        <p class="alert alert-danger">{{ Session::get('message') }}</p>
    </div>
@endif

<script>
    $('[name="username"]').focus();
</script>