@extends('layouts.master')

@section('content')
    <br />

    <h1>Testing</h1>

    <br />

    <?php
    if (Auth::check()) {
        echo 'Logged in!';
    } else {
        echo 'Not logged in :|';
    }

    //Debug::print_rci(Auth::user());
    ?>

    <br /><br />
    <h1>Create A New User</h1>

    <br />
    {{ Form::open(array('action' => 'UsersController@registerUser', 'files' => true)) }}

    {{Form::label('username', 'Username:')}}
    {{Form::text('username')}}

    <br /><br />

    {{Form::label('email', 'Email:')}}
    {{Form::email('email')}}

    <br /><br />

    {{Form::label('password', 'Password:')}}
    {{Form::password('password')}}

    <br /><br />

    {{Form::label('password_confirmation', 'Confirm Password:')}}
    {{Form::password('password_confirmation')}}

    <br /><br />

    {{Form::submit('Add User')}}

    {{ Form::close() }}

    <hr />

    <h1>All Users</h1>
    @forelse($users as $user)
        <li>{{ $user->username }}</li>
    @empty
        <p>No users</p>
    @endforelse
@stop

@section('modules')
    <aside class="module">
        <h1>Test Head</h1>
        <p>tttt</p>
    </aside>
@stop