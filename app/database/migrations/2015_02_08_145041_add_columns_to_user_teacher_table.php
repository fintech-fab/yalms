<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUserTeacherTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_teacher', function(Blueprint $table)
		{
			// где и на кого учился
			$table->string('education', 1000);
            // кем и сколько работал до начала преподавательской деятельности
			$table->string('job_before', 2000);
			// работает ли сейчас и если да, то кем
			$table->string('job_now', 200);
			// проходил ли, и если проходил, то где, специальное обучение на преподавателя
			$table->string('teacher_training', 400);
			// преподавательский стаж
			$table->integer('teaching_experience')->unsigned()->default(0);
			// методика обучения
			$table->string('training_technique', 5000);
			// требования к студенту
			$table->string('requirements_to_student', 2000);
			// всё что дополнительно хочет сообщить о себе
			$table->string('additional_info', 5000);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_teacher', function(Blueprint $table)
		{
			$table->dropColumn('education', 'job_before', 'job_now', 'teacher_training',
				'teaching_experience', 'training_technique', 'requirements_to_student',
				'additional_info');
		});
	}

}
