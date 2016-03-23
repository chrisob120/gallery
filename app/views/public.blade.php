@extends('layouts.master')

@section('content')
    <button class="dbtn right" data-toggle="tooltip" title="Favorite"><span class="glyphicon glyphicon-heart fav-icon"></span></button>
    <h1>{{ $album->album_title }}</h1>
    <h4 class="credit">Album created by <a href="#">{{ $album->user->username }}</a> on {{ $album->created_at }}</h4>

    @foreach($album->images as $image)
        <p>{{ $image->image_title }}</p>
        <img class="pub-image" src="{{ url(). '/' .$image->slug. '.' .$image->image_ext }}" alt="" />
    @endforeach

    <div class="action-bar">
        <button class="dbtn" data-toggle="tooltip" title="Up Vote"><span class="glyphicon glyphicon-heart fav-icon"></span></button>
        <button class="dbtn" data-toggle="tooltip" title="Down Vote"><span class="glyphicon glyphicon-heart fav-icon"></span></button>
    </div>

    <div class="mod-box">
        <h3>Comments</h3>

        <div class="content">
            <?php $c = 0; ?>
            @forelse($album->comments as $comment)
                <?php $c++; ?>
                <div class="c-container clearfix">
                    <div class="c-header">
                        <div class="num">#{{ $c }}</div>
                        <span class="flag"><img src="{{ url(). '/images/flags/' .strtolower($album->user->country->country_code). '.gif' }}" alt="" title="{{ $album->user->country->country_name }}" /></span>
                        <a class="username" href="{{ url(). '/profile/' .$album->user->username }}">{{ $album->user->username }}</a>
                        <span class="stats">with <span class="bold">{{ $comment->comment_score }}</span> votes and <span class="bold">X</span> replies</span>
                        <span class="right">{{ $comment->created_at }}</span>
                    </div>
                    <div class="c-vote">
                        <a href="#">up</a>
                        <a href="#">down</a>
                    </div>
                    <div class="c-content">{{ $comment->comment_text }}</div>
                </div>
            @empty
                <p>No comments for this album.</p>
            @endforelse
        </div>
    </div>
@stop