<?php

class UserController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| User Controller
	|--------------------------------------------------------------------------
	|
	| ttt
	|
	*/

    /**
     * User constructor
     */
    public function __construct() {
        parent::__construct();
    }

	public function getProfile($userInput) {
        $isInt = (is_numeric($userInput)) ? true : false;
        $userObj = ($isInt) ? User::where('user_id', '=', (int)$userInput) : User::where('username', '=', $userInput);

        // check if the user exists
        if ($userObj->exists()) {
            // get the user information
            $user = $userObj->first();

            $this->data['user'] = $user;
            $this->data['title'] = 'User - ' .$user->username;
            $this->data['publicAlbums'] = Album::getPublicAlbums($user->user_id);

            $this->modules = self::getModules('recprofile.usercnt');

            return View::make('profile', $this->data);
        } else {
            return Redirect::to('/');
        }
    }

}
