<?php

class Images extends Eloquent {

    /**
     * Name the table for the User object
     */
    protected $table = 'images';

    /**
     * Tell Eloquent the name of the user id field in the db
     */
    protected $primaryKey = 'image_id';

    /**
     * Allow fillable fields outside the class.
     * Only need 'user_id' in this case for creating the image in the db
     */
    protected $fillable = array('user_id');

    /**
     * Create the link between the album model
     *
     * @return object
     */
    public function album() {
        return $this->belongsTo('Album');
    }

    /**
     *
     * @return array
     */
    public function getImagesThisMonth() {

    }

}