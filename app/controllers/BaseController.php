<?php

class BaseController extends Controller {

	/**
	 * @var array Data array to pass data to views
	 */
	protected $data = array();

    /**
     * @var array Modules array to pass to views
     */
    protected $modules = array();

    /**
     * Create the login and upload views.
     */
    public function __construct() {
        $this->data['loginView'] = View::make('templates/tpl_login');
        $this->data['uploadView'] = View::make('templates/tpl_upload');
        $this->data['registerView'] = View::make('templates/tpl_register');

        // add modules as reference for modification within child class
        $this->data['modules'] = &$this->modules;

        // add user images
        $this->data['recentImages'] = (Auth::check()) ? Images::where('user_id', '=', Auth::user()->user_id)->orderBy('created_at', 'desc')->take(6)->get() : array();
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout() {
		if ( ! is_null($this->layout)) {
			$this->layout = View::make($this->layout);
		}
	}

    /**
     * Get module objects for a method
     *
     * @var string List of modules to load
     * @return array
     */
    protected function getModules($moduleStr) {
        $returnArr = array();
        $moduleArr = explode('.', $moduleStr);

        foreach ($moduleArr as $module) {
            $module = trim($module);
            $returnArr[] = View::make("modules/$module", $this->data)->render();
        }

        return $returnArr;

    }

    /**
     * Return a base64 slug
     *
     * @var int $baseNameStart
     * @return string
     */
    protected static function createSlug($baseNameStart) {
        $randNum = rand(20, 910000000);
        return base_convert($baseNameStart + $randNum, 10, 36); // base convert to 5 char string for the slug
    }

}
