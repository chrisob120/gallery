<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| ttt
	|
	*/

	public function index() {
		$this->data['title'] = 'Home Page';
		$this->data['tests'] = array('Test1', 'Test2', 'Test3');
		$this->data['users'] = User::all();
		$this->data['images'] = Images::take(16)->get();

		return View::make('home', $this->data);
	}

}
