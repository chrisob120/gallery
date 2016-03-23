<?php

class ImageController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Images Controller
	|--------------------------------------------------------------------------
	|
	| ttt
	|
	*/

    // MB
    const MAX_UPLOAD_SIZE = 20;
    const GIF_MAX_UPLOAD_SIZE = 50;

    // Base 10 img name start
    const BASE_IMG_NAME_START = 1700000;

	/**
	 * @var string imageFolder
	 */
	protected $imageFolder;

    /**
     * @var string tmpImgFolder
     */
    protected $tmpImgFolder;

    /**
     * $var string userImgFolder
     */
    protected $userImgFolder;

	/**
	 * @var array l33t
	 */
	protected $imageNameArr = array();

    /**
     * @var string current custom month path
     */
    protected $currentMonthPath;

    /**
     * @var string current month folder name
     */
    protected $currentMonthFolder;


	/**
	 * Image constructor
	 */
	public function __construct() {
        parent::__construct();

		$this->imageFolder = public_path(). '/uploads/images/';
		$this->tmpImgFolder = public_path(). '/uploads/tmp/';
        $this->userImgFolder = public_path(). '/uploads/users/';

		// set the imageArr array
		$this->imageNameArr = $this->getImageMonthArr();

        // check if current month folder exists and assign the current month path
        $this->checkMonthFolder();
	}

	public function index() {
	}

	/**
	 * Return all images for testing
	 *
	 * @return array
	 */
	public function getAllImages() {
		$returnArr = array();

		foreach (File::allFiles($this->imageFolder) as $file) {
			$fileName = $file->getRelativePathName();

			$returnArr[] = url() . '/uploads/images/'.$fileName;
		}

		return $returnArr;
	}

	/**
	 * Add image to que
     *
	 * @return array
	 */
	public function postAddImage() {
        if (Request::ajax()) {
            // A list of permitted file extensions
            $allowed = array('png', 'jpg', 'gif');

            $returnArr = array();
            $returnArr['error'] = false;

            if (Input::file('upl')->isValid() && $_FILES['upl']['error'] == 0) { // laravel check if valid
                $fullFileName = $this->tmpImgFolder . $_FILES['upl']['name'];
                $extension = pathinfo($fullFileName, PATHINFO_EXTENSION);

                if (!in_array(strtolower($extension), $allowed)) { // check extension
                    $returnArr['error'] = true;
                    $returnArr['msg'] = "The extension '$extension' is not allowed.";
                } else {
                    $fileSize = $this->convertFileSize($_FILES['upl']['size'], 'MB');

                    if ($extension != 'gif' && $fileSize <= self::MAX_UPLOAD_SIZE || $extension == 'gif' && $fileSize <= self::GIF_MAX_UPLOAD_SIZE) { // check on size
                        if (!File::exists($fullFileName)) { // make sure file isn't already in temp folder
                            $fileSpaceReplace = str_replace(' ', '_', $_FILES['upl']['name']);

                            if (Input::file('upl')->move($this->tmpImgFolder, $fileSpaceReplace)) { // attempt to move file to tmp folder
                            } else {
                                $returnArr['error'] = true;
                                $returnArr['msg'] = 'There was an error moving the file.';
                            }
                        }
                    } else {
                        $returnArr['error'] = true;
                        $returnArr['msg'] = 'The file your are trying to upload is too large.';
                    }
                }
            } else {
                $returnArr['error'] = true;
                $returnArr['msg'] = 'There was an error uploading this file.';
            }

            return Response::json($returnArr);
        }
	}

    /**
     * Upload le images
     *
     * @return array
     */
    public function postUploadImages() {
        if (Request::ajax()) {
            $filesToUpload = array_map(function($el) { return str_replace(' ','_',$el); }, Input::get('files'));
            $tmpFiles = array_map('basename', File::allFiles($this->tmpImgFolder));

            $returnArr = array();
            $returnArr['error'] = false;

            foreach ($filesToUpload as $file) {
                if (in_array($file, $tmpFiles)) {
                    $filePath = $this->tmpImgFolder . $file;

                    if (File::exists($filePath)) {
                        $tmpArr['tmpName'] = $file;
                        $tmpArr['size'] = File::size($filePath);
                        $tmpArr['type'] = File::type($filePath);
                        $tmpArr['ext'] = File::extension($filePath);

                        // find the dimensions
                        list($width, $height) = getimagesize($filePath);

                        $tmpArr['width'] = $width;
                        $tmpArr['height'] = $height;

                        // create image db entry
                        $dbImageData = Images::create(array());

                        // get the new image object
                        $imageObj = Images::find($dbImageData->image_id);

                        if (Auth::check()) $imageObj->user_id = Auth::user()->user_id; // only add user id if logged in
                        $imageObj->slug = $this->createImageName();
                        $imageObj->image_ext = $tmpArr['ext'];
                        $imageObj->image_folder = $this->currentMonthFolder;
                        $imageObj->image_size = $tmpArr['size'];
                        $imageObj->image_width = $tmpArr['width'];
                        $imageObj->image_height = $tmpArr['height'];
                        $imageObj->uploaded_ip = Request::getClientIp();

                        $imageObj->save();

                        // move the file from temp
                        if (File::move($filePath, $this->currentMonthPath. '/' .$imageObj->slug. '.' .$imageObj->image_ext)) {
                            $this->removeImages($tmpArr['tmpName']);
                            $returnArr['images'][]['imgPath'] = url() . '/uploads/images/' . $this->currentMonthFolder. '/' .$imageObj->slug. '-thumbnail(_x80).' .$imageObj->image_ext;
                        }
                    }
                }
            }

            return Response::json($returnArr);
        }

    }

    /**
     * Post remove temporary image
     *
     * @return object
     */
    public function postRemoveTmpImages() {
        if (Request::ajax()) {
            $images = Input::get('images');
            $this->removeImages($images);

            return Response::json($images);
        }
    }

    /**
     * Remove images from the servers
     *
     * @param mixed $images
     * @param string $type
     *
     * @return void
     */
    private function removeImages($images, $type = 'temp') {
        // if it's a single file (not an array), put the file in an array
        $imageArr = (!is_array($images)) ? array($images) : $images;
        $folder = ($type == 'temp') ? $this->tmpImgFolder : $this->imageFolder;

        foreach ($imageArr as $img) {
            if (File::exists($folder . $img)) {
                File::delete($folder . $img);
            }
        }

        if ($type == 'images') {
            // possibly add delete tracker
        }
    }

    /**
     * Check if the month folder exists and if it does not, create it
     *
     * @return void
     */
    private function checkMonthFolder() {
        $monthArr = $this->getImageMonthArr();
        $monthNum = date('n');
        $year = date('Y');
        $customMonthName = $monthArr[$monthNum];

        $folderName = $year . $customMonthName;

        // check if folder exists
        if (!File::exists($this->imageFolder . $folderName)) {
            // create directory
            File::makeDirectory($this->imageFolder . $folderName, 0775);
        }

        $this->currentMonthPath = $this->imageFolder . $folderName;
        $this->currentMonthFolder = $folderName;
    }

	/**
	 * Get dem months
	 *
	 * @return array
	 */
	private function getImageMonthArr() {
        $imageArr = array();

        $imageArr[1] = 'r3dn3b'; // Bender
        $imageArr[2] = 'yrfjp1l1hp'; // Phillip J Fry
        $imageArr[3] = '4l33l46n4ru7'; // Turanga Leela
        $imageArr[4] = '6n0wym4'; // Amy Wong
        $imageArr[5] = 'd4rn0c53mr3h'; // Hermes Conrad
        $imageArr[6] = 'h7r0w5nr4fj7r3buh'; // Hubert J Farnsworth
        $imageArr[7] = '6r3bd10znh0j'; // John Zoidberg
        $imageArr[8] = 'r3lbb1ndr0l'; // Lord Nibbler
        $imageArr[9] = 'n461nn4rbpp4z'; // Zapp Brannigan
        $imageArr[10] = 'r3k0rkf1k'; // Kif Kroker
        $imageArr[11] = 'yffurc5'; // Scruffy
        $imageArr[12] = 'n0lucl4c'; // Calculon

        return $imageArr;
	}

    /**
     * Format bytes
     *
     * @param int $bytes
     * @param string $type
     *
     * @return float
     */
    private function convertFileSize($bytes, $type = null) {

        if ($type == 'GB') {
            $size = number_format($bytes / 1073741824, 2);
        } else if ($type == 'MB') {
            $size = number_format($bytes / 1048576, 2);
        } else if ($type == 'KB') {
            $size = number_format($bytes / 1024, 2);
        } else {
            $size = $bytes;
        }

        return $size;
    }

    /**
     * Create a new image name
     *
     * @return string
     */
    private function createImageName() {
        $nameFree = true;

        // loop thru until a name is free (probably instantly)
        // caveat: i was lazy and decided to go thru entire images table.
        // could be a problem in the future if db got huge...
        do {
            // create a slug
            $name = parent::createSlug(self::BASE_IMG_NAME_START);

            // see if the slug is already in the db
            $count = Images::where('slug', $name)->count();

            if ($count > 0) break;
        } while(!$nameFree);

        return $name;
    }

    /**
     * Show a single image page
     *
     * @var string $imageSlug
     * @return mixed
     */
    public function getImage($imageSlug)
    {
        $imageSlugArr = explode('.', $imageSlug);
        $slug = $imageSlugArr[0];

        // if the request is a regular slug
        if (count($imageSlugArr) == 1) {
            $image = Images::where('slug', '=', $slug);

            if ($image->exists()) {
                $image = $image->first();

                $this->data['image'] = $image;
                $this->data['title'] = 'Gallery';

                $this->modules = self::getModules('links.imagedetails.userimageoptions');

                return View::make('singleImage', $this->data);
            } else {
                return App::abort(404);
            }
        } else { // output the actual image
            $ext = $imageSlugArr[1];
            $image = Images::where('slug', '=', $slug)->where('image_ext', '=', $ext);

            if ($image->exists()) {
                $image = $image->first();
                $imagePath = $this->imageFolder . $image->image_folder. '/' .$image->slug. '.' .$image->image_ext;

                if (File::exists($imagePath)) { // create the new file
                    $imageFile = File::get($imagePath);

                    return Response::make($imageFile, 200, ['content-type' => 'image/jpg']);
                } else {
                    echo 'Image does not exit page. (NEED TO UPDATE THIS)';
                }
            } else {
                echo 'Image does not exit page. (NEED TO UPDATE THIS)';
            }
        }

    }

    public function postUpdateUserImage() {
        // A list of permitted file extensions
        $allowed = array('png', 'jpg', 'gif');
        $error = false;
        $msg = '';
        $curUser = User::find((int)Input::get('userId'));

        if (Input::file('userImgUpl')->isValid() && $_FILES['userImgUpl']['error'] == 0) { // laravel check if valid
            $extension = Input::file('userImgUpl')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $allowed)) { // check extension
                $error = true;
                $msg = "The extension '$extension' is not allowed.";
            } else {
                $fileSize = $this->convertFileSize($_FILES['userImgUpl']['size'], 'MB');

                if ($extension != 'gif' && $fileSize <= self::MAX_UPLOAD_SIZE || $extension == 'gif' && $fileSize <= self::GIF_MAX_UPLOAD_SIZE) { // check on size
                    $newFileName = $curUser->user_id. '.' .$extension;

                    list($width, $height) = getimagesize(Input::file('userImgUpl'));

                    if ($width == $height) { // check to see if the image is a square
                        // remove old picture
                        File::delete($this->userImgFolder . $curUser->profile_img);

                        if (Input::file('userImgUpl')->move($this->userImgFolder, $newFileName)) { // attempt to move file to user image folder
                            $curUser->profile_img = $newFileName;

                            if ($curUser->save()) { // check if it saves correctly to the db
                            } else {
                                $error = true;
                                $msg = 'The image uploaded successfully, but there was an error updating the database.';
                            }
                        } else {
                            $error = true;
                            $msg = 'There was an error moving the file.';
                        }
                    } else {
                        $error = true;
                        $msg = 'The image must have equal width and height.';
                    }
                } else {
                    $error = true;
                    $msg = 'The file your are trying to upload is too large.';
                }
            }
        } else {
            $error = true;
            $msg = 'There was an error uploading this file.';
        }

        if ($error) {
            return Redirect::to('/profile/' .$curUser->username)
                ->with('error', 'Error: ' .$msg);
        } else {
            return Redirect::to('/profile/' .$curUser->username)
                ->with('success', 'Picture Updated.');
        }
    }

    /**
     * Show a public image or album
     *
     * @var string $slug
     * @return mixed
     */
    public function getPublicAlbumImage($slug) {
        $pubObj = Pub::where('public.p_slug', '=', $slug);

        if ($pubObj->exists()) {
            if ($pubObj->first()->type == 'album') {
                $user_id = $pubObj->first()->user_id;

                $pubObj = $pubObj
                    ->join('albums', 'public.type_id', '=', 'albums.album_id')
                    ->first();
                $pubObj->user = User::find($user_id);
                $pubObj->images = Images::where('album_id', '=', $pubObj->album_id)->get();
                $pubObj->comments = Comment::where('type', '=', 'album')->where('type_id', '=', $pubObj->album_id)->orderBy('created_at')->get();
            } else {
                $pubObj = $pubObj->join('images', 'public.type_id', '=', 'images.image_id')->first();
            }

            $this->data['album'] = $pubObj;
            $this->data['title'] = 'Gallery - Public';

            //$this->modules = self::getModules('');

            return View::make('public', $this->data);
        } else {
            return App::abort(404);
        }
    }

}
