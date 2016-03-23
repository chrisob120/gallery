@extends('layouts.main')

@section('contentChoice')
	<div id="content" class="content-full">
@stop

@section('content')

	<div id="sort">
        <!--
		<div class="drop">
			<div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					All Images <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><a href="#">Your Images</a></li>
				</ul>
			</div>

			<div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					New Images <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><a href="#">Popular</a></li>
				</ul>
			</div>
		</div>
            -->
        <form class="navbar-form" role="search">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </form>
	</div>
    <!--
	<div id="grid">
		@forelse($images as $image)
			<div class="img-holder"><a href="{{ url(). '/' .$image->slug }}">{{ HTML::image(url(). '/uploads/images/' . $image->image_folder. '/' .$image->slug. '.'  .$image->image_ext, $alt='', $attributes = array()) }}</a></div>
		@empty
			<div class="img-holder">No images found!</div>
		@endforelse
	</div>
	-->

    <br />
    <h1>Images Hidden For Now</h1>
@stop

@section('inlineScripts')
	@parent
	{{ HTML::script('js/grid-a-licious.min.js') }}
	<script type="text/javascript">
		$("#grid").gridalicious({
			gutter: 1,
			width: 250,
			selector: '.img-holder'
		});
	</script>
@stop