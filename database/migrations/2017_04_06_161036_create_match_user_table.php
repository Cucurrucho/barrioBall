<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchUserTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('match_user', function (Blueprint $table) {
			$table->integer('user_id')->unsigned();
			$table->integer('match_id')->unsigned();
			$table->string('role');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
			$table->unique(['user_id', 'match_id', 'role']);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('match_user');
	}
}
