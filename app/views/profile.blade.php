@extends('layouts.master')

@section('content')
    <div class="head-box clearfix">
        <div class="profile-img">
            {{ !empty($user->profile_img) ? HTML::image('/uploads/users/' .$user->profile_img, 'Profile Picture', array('class' => 'user-img')) : HTML::image('/images/default_large.png', 'Profile Picture', array('class' => 'user-img')) }}
            {{ (Auth::check() && $user->user_id == Auth::user()->user_id) ? '<span id="img-change">Change Picture</span>' : '' }}

            {{-- Hidden user upload form --}}
            {{ Form::open(array('action' => 'ImageController@postUpdateUserImage', 'files' => true)) }}
            {{ Form::file('userImgUpl', array('id' => 'usrImgField', 'style' => 'display: none;')); }}
            {{ Form::hidden('userId', $user->user_id) }}
            {{ Form::close() }}
        </div>
        <h1>{{ $user->username }}</h1>
        <p>Member since {{ date('F, Y', strtotime($user->joined)) }}</p>
        <span class="badge online">Online</span>
    </div>

    <div class="rep-box">
        <h3 class="header">User Information</h3>
        <ul class="clearfix">
            <li>
                <span class="r-title">Group</span>
                <span class="r-data"><a style="color: {{ $user->group->color }};" href="#">{{ ucfirst($user->group->group_name)  }}</a></span>
            </li>
            <li>
                <span class="r-title">Country</span>
                <span class="r-data">{{ HTML::image('/images/flags/' .strtolower($user->country->country_code). '.gif', $user->country->country_name, array('title' => $user->country->country_name)) }}</span>
            </li>
            <li>
                <span class="r-title">Name</span>
                <span class="r-data">{{ !empty($user->f_name) ? $user->f_name : 'Not Set' }}</span>
            </li>
            <li>
                <span class="r-title">Location</span>
                <span class="r-data">{{ !empty($user->location) ? $user->location : 'Not Set' }}</span>
            </li>
        </ul>

        <h3 class="header">Interactions</h3>
        <ul class="clearfix">
            <li>
                <span class="r-title">Submissions</span>
                <span class="r-data">{{ count($user->pub) }}</span>
            </li>
            <li>
                <span class="r-title">Comments</span>
                <span class="r-data">{{ count($user->comments) }}</span>
            </li>
        </ul>

        <h3 class="header">Public Galleries</h3>
        @forelse($publicAlbums as $album)
            <div class="album-hold clearfix">
                <h4>{{ $album->album_title }}</h4>
                <a class="album" href="{{ url(). '/p/' .$album->pub_slug }}" title="View Album">
                    <div class="album-container">
                        <img class="prev" src="{{ url(). '/' .$album->cover->slug. '.' .$album->cover->image_ext }}" />
                        <div class="album-stats">
                            <div class="split left-split"><img class="icons" src="{{ url(). '/images/camera_icon.png' }}" alt="" /> <span>{{ $album->img_count }}</span></div>
                            <div class="split right-split"><img class="icons" src="{{ url(). '/images/comment_icon.png' }}" alt="" /> <span>{{ $album->comment_count }}</span></div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="empty">
                <img src="{{ url(). '/images/squirrel.png' }}" alt="" />
                <p class="nfind">Nuttin to see here :(</p>
            </div>
        @endforelse

    </div>

    @if(Session::has('error'))
        <div id="msg-box" class="disappear">
            <p class="alert alert-danger">{{ Session::get('error') }} (<span class="counter">5</span>)</p>
        </div>
    @elseif (Session::has('success'))
        <div id="msg-box" class="disappear">
            <p class="alert alert-success">{{ Session::get('success') }} (<span class="counter">3</span>)</p>
        </div>
    @endif
@stop