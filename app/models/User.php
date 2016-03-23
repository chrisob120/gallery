<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    /**
     * Name the table for the User object
     */
    protected $table = 'users';

    /**
     * Tell Eloquent the name of the user id field in the db
     */
    protected $primaryKey = 'user_id';

    /**
     * Prevent Eloquent from trying to add the timestamps to DB for Users
     */
    public $timestamps = false;

    /**
     * Generic new user rules
     */
    public static $newUserRules = array(
        'username' => 'required|alpha_num|between:3,20|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required'
    );

    /**
     * Get the remember token.
     *
     * @return string
     */
    public function getRememberToken() {
        return $this->remember_token;
    }

    /**
     * Set the remember token.
     *
     * @return void
     */
    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    /**
     * Get the member token name.
     *
     * @return string
     */
    public function getRememberTokenName() {
        return 'remember_token';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }


    /**
     * Create a new user and return the object
     *
     * @return object
     */
    public static function createUser() {
        $user = new User();
        $user->group_id = 5;
        $user->username = Input::get('username');
        $user->email = Input::get('email');
        $user->joined = date('Y-m-d');
        $user->password = Hash::make(Input::get('password'));

        // create user
        $user->save();

        // now md5 insert confirmation token with new user id
        $user->confirm_token = md5($user->username . microtime());
        $user->save();

        return $user;
    }

    /**
     * Create the link between the country model
     *
     * @return object
     */
    public function country() {
        return $this->belongsTo('Country');
    }

    /**
     * Create the link between the group model
     *
     * @return object
     */
    public function group() {
        return $this->belongsTo('Group');
    }

    /**
     * Create the link between the comment model
     *
     * @return object
     */
    public function comments() {
        return $this->hasMany('Comment');
    }

    /**
     * Create the link between the comment model
     *
     * @return object
     */
    public function pub() {
        return $this->hasMany('Pub');
    }

}