@extends('layouts.empty')

@section('content')
    <!--{{HTML::link('/', 'Back to {Galley Name}')}}-->

    {{ $registerView }}

@stop

@section('inlineScripts')
    @parent
@stop