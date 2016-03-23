<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('albums', function($table)
		{
			// set engine
			$table->engine = 'InnoDB';

			$table->increments('album_id');
			$table->integer('user_id');
			$table->integer('album_permission_id');
			$table->string('album_name');
			$table->integer('album_views');
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
		Schema::drop('albums');
	}

}
