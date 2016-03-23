<aside class="module" id="side-links">
    <h1>Links</h1>
    <div class="hold">
        <label>Image Page Link</label>
        {{ Form::text('username', url(). '/' .$image->slug, array('readonly')) }}

        <label>Direct Link</label>
        {{ Form::text('username', url(). '/' .$image->slug. '.' .$image->image_ext, array('readonly')) }}

        <label>HTML Link</label>
        {{ Form::text('username', '<img src="' .url(). '/' .$image->slug. '.' .$image->image_ext. '" alt="' .$image->slug. '" />', array('readonly')) }}

        <label>BBCode Link</label>
        {{ Form::text('username', '[img]' .url(). '/' .$image->slug. '.' .$image->image_ext. '[/img]', array('readonly')) }}
    </div>

    <!-- start template specific js -->
    <script>
        $('input[type="text"]').click(function () {
            $(this).select();
        });
    </script>
    <!-- end template specific js -->
</aside>