<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUserStudentTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_student', function (Blueprint $table) {

			/**
			 * где и на кого учился
			 */
			$table->text('education');
			/**
			 *  кем и сколько работал
			 */
			$table->text('job_before');
			/**
			 *  работает ли сейчас и если да, то кем
			 */
			$table->string('job_now', 200);
			/**
			 *  пожелания по методике обучения
			 */
			$table->text('wishes_by_a_training_technique');
			/**
			 * всё что дополнительно хочет сообщить о себе
			 */
			$table->text('additional_info');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_student', function (Blueprint $table) {
			$table->dropColumn('education', 'job_before', 'job_now',
				'wishes_by_a_training_technique', 'additional_info');
		});
	}

}
