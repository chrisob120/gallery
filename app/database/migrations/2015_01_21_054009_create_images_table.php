<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('images', function($table)
		{
			// set engine
			$table->engine = 'InnoDB';

			$table->increments('image_id');
			$table->integer('user_id')->nullable()->default(null);
			$table->string('slug');
            $table->string('image_ext');
            $table->string('image_title');
			$table->text('image_user_comments');
			$table->string('image_folder');
			$table->string('image_size');
			$table->integer('image_width');
			$table->integer('image_height');
			$table->integer('image_views')->default(0);
			$table->string('uploaded_ip', 20);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('images');
	}

}
