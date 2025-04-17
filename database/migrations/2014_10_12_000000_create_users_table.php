<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'bank_mini', 'siswa']);
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->rememberToken();
            $table->timestamps();
            $table->boolean('is_admin')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};