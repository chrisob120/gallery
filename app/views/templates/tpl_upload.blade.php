{{ Form::open(array('id' => 'upload', 'action' => 'ImageController@postAddImage', 'files' => true)) }}
<div id="drop">
    <p>Drop Here</p>
    <a>Browse</a>
    {{ Form::file('upl', array('multiple')); }}
</div>

<ul>
    <!-- File uploads will be shown here -->
</ul>

<div id="options" style="display: none;">
    <p class="img-cnt">Images: <span class="img-count bold"></span></p>
    <a class="upload"><span class="glyphicon glyphicon-cloud"></span> Upload</a>
    <div class="clear"></div>
</div>
{{ Form::close() }}