@if ($image->user_id == Auth::user()->user_id)
<aside class="module" id="user-image-options">
    <h1>Image Options</h1>
    <ul>
        <li><a href="#">Test</a></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</aside>
@endif