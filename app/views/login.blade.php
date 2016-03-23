@extends('layouts.empty')

@section('content')
	<!--{{HTML::link('/', 'Back to {Galley Name}')}}-->

    {{ $loginView }}

@stop

@section('inlineScripts')
	@parent
@stop