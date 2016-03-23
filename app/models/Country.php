<?php

class Country extends Eloquent {

    /**
     * Name the table for the User object
     */
    protected $table = 'countries';

    /**
     * Tell Eloquent the name of the user id field in the db
     */
    protected $primaryKey = 'country_id';


}