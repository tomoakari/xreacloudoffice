<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsUsersTable extends Migration
{
        public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->string('name_pronunciation')->nullable();
          $table->integer('birth_year')->nullable();
          $table->integer('birth_month')->nullable();
          $table->integer('birth_day')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('name_pronunciation');
          $table->dropColumn('birth_year');
          $table->dropColumn('birth_month');
          $table->dropColumn('birth_day');
        });
    }
}
