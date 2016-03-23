<?php

class Comment extends Eloquent {

    /**
     * Name the table for the User object
     */
    protected $table = 'comments';

    /**
     * Tell Eloquent the name of the user id field in the db
     */
    protected $primaryKey = 'comment_id';


}