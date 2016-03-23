<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('users', function($table)
		{
			// set engine
			$table->engine = 'InnoDB';

			$table->increments('user_id');
			$table->integer('group_id')->default(4);
			$table->string('username');
			$table->string('password');
			$table->string('email');
			$table->string('f_name', 70);
			$table->string('l_name', 70);
			$table->string('location');
			$table->integer('country_id')->default(0);
			$table->date('joined');
			$table->dateTime('last_login');
			$table->string('last_ip', 20);
			$table->date('dob');
			$table->string('profile_img');
			$table->integer('profile_views')->default(0);
			$table->rememberToken();
            $table->string('confirm_token');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
