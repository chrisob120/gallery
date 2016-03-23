@extends('layouts.master')

@section('extraHead')
    <!-- Template Specific CSS -->
    <style>
        .content-block { min-height: inherit !important; }
    </style>
@stop

@section('content')
    <div class="image-container">
        <a href="{{ url(). '/' .$image->slug. '.' .$image->image_ext }}" target="_blank">{{ HTML::image('/uploads/images/' .$image->image_folder. '/' .$image->slug. '.' .$image->image_ext, $image->title) }}</a>
    </div>
@stop