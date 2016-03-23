<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('album_permissions', function($table)
		{
			// set engine
			$table->engine = 'InnoDB';

			$table->increments('album_permission_id');
			$table->string('permission_name');
			$table->string('permission_description');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('album_permissions');
	}

}
