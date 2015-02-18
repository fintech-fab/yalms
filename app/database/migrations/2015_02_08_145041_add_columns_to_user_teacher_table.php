<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUserTeacherTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_teacher', function (Blueprint $table) {
			/**
			 *  где и на кого учился
			 */
			$table->text('education');
			/**
			 *  кем и сколько работал до начала преподавательской деятельности
			 */
			$table->text('job_before');
			/**
			 *  работает ли сейчас и если да, то кем
			 */
			$table->string('job_now', 200);
			/*
			 * проходил ли, и если проходил, то где, специальное обучение на преподавателя
			 */
			$table->text('teacher_training');
			/**
			 *  преподавательский стаж
			 */
			$table->unsignedInteger('teaching_experience')->default(0);
			/**
			 * методика обучения
			 */
			$table->text('training_technique');
			/**
			 * требования к студенту
			 */
			$table->text('requirements_to_student');
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
		Schema::table('user_teacher', function (Blueprint $table) {
			$table->dropColumn('education', 'job_before', 'job_now', 'teacher_training',
				'teaching_experience', 'training_technique', 'requirements_to_student',
				'additional_info');
		});
	}

}
