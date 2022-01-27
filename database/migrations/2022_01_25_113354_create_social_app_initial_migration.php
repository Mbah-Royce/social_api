<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialAppInitialMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone1');
            $table->string('phone2');
            $table->decimal('lat',35,30)->nullable();
            $table->decimal('lng',35,30)->nullable();
            $table->string('logo');
            $table->string('password');
            $table->string('cover_picture')->nullable();
            $table->enum('account_status',['active','suspended','blocked','disactivated','inactive'])->default('inactive');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('class_rooms', function (Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools');
        });

        Schema::create('courses', function (Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->unsignedBigInteger('class_room_id');
            $table->foreign('class_room_id')->references('id')->on('class_rooms');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->date('dob');
            $table->enum('gender',['male','female','other']);
            $table->string('profile_picture')->nullable();
            $table->string('cover_picture')->nullable();
            $table->boolean('pwd_changed')->default(false);
            $table->enum('account_status',['active','suspended','blocked','disactivated','inactive'])->default('inactive');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('desciption');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('desciption');
            $table->timestamps();
        });

        Schema::create('role_user',function(Blueprint $table){
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('school_id')->nullable();

            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');

            $table->primary(['user_id','role_id']);
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('class_room_id');
            $table->string('level');
            $table->string('field')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('class_room_id')->references('id')->on('class_rooms');
            $table->string('current_address')->nullable();
            $table->enum('account_status',['active','suspended','blocked','disactivated','inactive'])->default('inactive');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        Schema::create('teacher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_id');
            $table->string('qualificaton');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->enum('account_status',['active','suspended','blocked','disactivated','inactive'])->default('inactive');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        Schema::create('course_teacher', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('course_id');
            $table->string('qualificaton');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->primary(['teacher_id','course_id']);
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
        Schema::dropIfExists('schools');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('class_rooms');
        Schema::dropIfExists('school_student');
        Schema::dropIfExists('course_teacher');
    }
}
