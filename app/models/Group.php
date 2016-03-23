<?php

class Group extends Eloquent {

    /**
     * Name the table for the User object
     */
    protected $table = 'groups';

    /**
     * Tell Eloquent the name of the user id field in the db
     */
    protected $primaryKey = 'group_id';


}