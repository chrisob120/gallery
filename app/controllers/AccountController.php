<?php

class AccountController extends BaseController {

    /**
     * Account constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Login method
     *
     * @return mixed
     */
	public function login() {
        // ajax login
        if (Request::ajax()) {
            $data = array();

            // check if user exists
            if (User::where('username', Input::get('username'))->first()) {
                // try authenticating
                if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')), true)) {
                    $data['error'] = false;
                    $data['returnUrl'] = URL::previous();
                } else {
                    $data['error'] = true;
                    $data['msg'] = 'The password you entered is incorrect.';
                    $data['field'] = 'password';
                }
            } else {
                $data['error'] = true;
                $data['msg'] = 'The username you entered does not exist.';
                $data['field'] = 'username';
            }

            $data['fields'] = array('username', 'password');

            return Response::json($data);
        } else {
            // if already logged in, redirect to homepage
            if (Auth::check()) Redirect::to('/');

            // if form was clicked
            if (Input::all()) {
                // check if user exists
                if (User::where('username', Input::get('username'))->first()) {
                    if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')), true)) {
                        return Redirect::intended('/');
                    } else {
                        return Redirect::to('/login')
                            ->with('message', 'Error: The password you entered is incorrect.')
                            ->withInput();
                    }
                } else {
                    return Redirect::to('/login')
                        ->with('message', 'Error: The username you entered does not exist.')
                        ->withInput();
                }
            }

            $this->data['title'] = 'Login';

            return View::make('login', $this->data);
        }
	}

    /**
     * Logout method
     *
     * @return mixed
     */
	public function logout() {
		Auth::logout();

		return Redirect::intended('/');
	}

    /**
     * Registration method
     *
     * @return mixed
     */
    public function register() {
        // ajax register
        if (Request::ajax()) {
            $data = array();
            $data['error'] = false;

            $validator = Validator::make(Input::all(), User::$newUserRules);

            // if there are no errors
            if (!$validator->passes()) {
                $data['error'] = true;
                $data['errors'] = $validator->messages();
            } else {
                // create the user
                $user = User::createUser();

                // send activation email
                Mail::send('emails.auth.activate', array('link' => URL::route('activate', $user->confirm_token), 'username' => $user->username), function($message) use ($user) {
                    $message->from('chris@chrisob.com', 'User Accounts');
                    $message->to($user->email)->subject('Gallery Account Activation');
                });

                //$data['returnUrl'] = url(). '/profile';
                $data['returnUrl'] = url(). '/users';
            }

            $data['fields'] = array('username', 'email', 'password');

            return Response::json($data);
        } else {
            // if already logged in, redirect to homepage
            if (Auth::check()) Redirect::to('/');

            // if form was clicked
            if (Input::all()) {

            }
        }

        $this->data['title'] = 'Register';

        return View::make('register', $this->data);
    }

    /**
     * Activate a user account
     *
     * @var string Confirmation token
     * @return mixed
     */
    public function activate($token) {
        $user = User::where('confirm_token', '=', $token)->where('group_id', '=', 5);

        if ($user->count()) {
            $user = $user->first();

            // make user a regular member
            $user->group_id = 3;
            $user->confirm_token = '';

            if ($user->save()) {

            }
        } else {
            return Redirect::to('/');
        }
    }


}
