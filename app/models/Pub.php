<?php

class Pub extends Eloquent {

    /**
     * Name the table for the User object
     */
    protected $table = 'public';

    /**
     * Tell Eloquent the name of the user id field in the db
     */
    protected $primaryKey = 'pid';

    /**
     * Allow fillable fields outside the class.
     * Only need 'user_id' in this case for creating the image in the db
     */
    protected $fillable = array();

    /**
     * Create the link between the album model
     *
     * @return object
     */
    public function album() {
        return $this->belongsTo('Album');
    }

    /**
     * Create the link between the images model
     *
     * @return object
     */
    public function images() {
        return $this->belongsTo('Images');
    }


    /**
     *
     * @return array
     */
    public function getImagesThisMonth() {

    }

}