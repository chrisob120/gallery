<aside class="module" id="rec-prof">
    @if (Auth::check())
        <h1>Hello, <span class="bold">{{ Auth::user()->username }}</span></h1>
        <div class="inner">
            <h3 class="center bold">Your Recent Uploads</h3>
            <div class="recent-images">
                @forelse ($recentImages as $image)
                    <div class="hold">
                        <a href="{{ url(). '/' .$image->slug }}">{{ HTML::image(url(). '/uploads/images/' . $image->image_folder. '/' .$image->slug. '.'  .$image->image_ext, $alt='', $attributes = array()) }}</a>
                    </div>
                @empty
                    You have not uploaded any images.
                @endforelse
                <div class="clear"></div>
            </div>
            <!--<a class="all-images" href="#">View All Images</a>-->
        </div>
    @else
        <h1>Nothing to display.</h1>
    @endif
</aside>