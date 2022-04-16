<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('email_verified')->default(0);
            $table->string('email_verify_token')->nullable();
            $table->tinyInteger('status')->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified');
            $table->dropColumn('email_verify_token');
            $table->dropColumn('status');
        });
    }
}
