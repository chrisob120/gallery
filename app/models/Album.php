<?php

class Album extends Eloquent {

    /**
     * Name the table for the User object
     */
    protected $table = 'albums';

    /**
     * Tell Eloquent the name of the user id field in the db
     */
    protected $primaryKey = 'album_id';

    /**
     * Allow fillable fields outside the class.
     * Only need 'user_id' in this case for creating the image in the db
     */
    protected $fillable = array();

    /**
     * Create the link between the images model
     *
     * @return object
     */
    public function images() {
        return $this->hasMany('Images');
    }

    /**
     * Find all of the public albums for the User.
     *
     * @var int $user_id
     * @return object
     */
    public static function getPublicAlbums($user_id) {
        $returnArr = array();
        $user = User::find($user_id);
        $albums = DB::table('public')
                            ->join('albums', 'public.type_id', '=', 'albums.album_id')
                            ->where('public.type', '=', 'album')
                            ->where('albums.user_id', '=', $user->user_id)
                            ->get();

        if ($albums) {
            $cnt = 1;

            foreach ($albums as $album) {
                $albumHasImage = Images::where('user_id', '=', $user->user_id)->where('album_id', '=', $album->album_id)->orderByRaw("RAND()")->take(1)->get()->first();

                if (isset($albumHasImage) && $albumHasImage->count() > 0) {
                    $returnArr[$cnt] = new stdClass();
                    $returnArr[$cnt]->album_title = $album->album_title;
                    $returnArr[$cnt]->cover = $albumHasImage;
                    $returnArr[$cnt]->img_count = Images::where('user_id', '=', $user->user_id)->where('album_id', '=', $album->album_id)->count();
                    $returnArr[$cnt]->comment_count = Comment::where('user_id', '=', $user->user_id)->where('type', '=', 'album')->where('type_id', '=', $album->album_id)->count();
                    $returnArr[$cnt]->pub_slug = $album->p_slug;

                    $cnt++;
                }

            }
        }

        return $returnArr;
    }

}