<div id="login-box" class="inline box-hold">
    <div class="logo">Logo</div>
    <h1>{Gallery Name} Login</h1>
    <div id="login-hold">
        {{ Form::open(array('action' => 'AccountController@login', 'name' => 'loginTest')) }}
        <p class="field" data-toggle="tooltip" title="">
            {{Form::text('username', '', array('placeholder'=>'Username', 'required'))}}
            <span class="glyphicon glyphicon-user"></span>
        </p>
        <p class="field" data-toggle="tooltip" title="">
            {{Form::password('password', array('placeholder' => 'Password', 'required'))}}
            <span class="glyphicon glyphicon-lock"></span>
        </p>
        <p class="submit">
            <button type="submit" name="submit">
                <span class="glyphicon glyphicon-arrow-right"></span>
            </button>
        </p>
        {{ Form::close() }}

        <p class="register-text small-text">{{HTML::link('#', "Forgotten Password")}} | <a{{ (Request::is('login')) ? '' : ' class="fancyregister"' }} href="{{URL::to('/register')}}">Create Account</a></p>
    </div>
    <div class="bottom small-text">
        <div class="footer">Copywrite 2015 Blah Blah Gallery Stuff</div>
    </div>
</div>

@if(Session::has('message'))
    <div id="msg-box">
        <p class="alert alert-danger">{{ Session::get('message') }}</p>
    </div>
@endif

<script>
    $('[name="username"]').focus();
</script>