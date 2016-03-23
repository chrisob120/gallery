<aside class="module" id="image-details">
    <h1>Image Details</h1>
    <table>
        <caption>{{ $image->slug. '.' .$image->image_ext }}</caption>
        <tbody>
            <tr>
                <td>Dimensions:</td>
                <td>{{ $image->image_width }} x {{ $image->image_height }} (px)</td>
            </tr>
            <tr>
                <td>Size:</td>
                <td>{{ $image->image_size }}</td>
            </tr>
            <tr>
                <td>Views:</td>
                <td>{{ $image->image_views }}</td>
            </tr>
            <tr>
                <td>Uploaded:</td>
                <td>{{ $image->created_at }}</td>
            </tr>
        </tbody>
    </table>
</aside>