<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function($table)
		{
			// set engine
			$table->engine = 'InnoDB';

			$table->increments('comment_id');
			$table->integer('image_id');
			$table->integer('user_id');
			$table->text('comment_text');
			$table->integer('comment_score')->default(0);
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
		Schema::drop('comments');
	}

}
