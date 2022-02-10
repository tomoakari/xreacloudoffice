<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrolledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrolleds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userid');
            $table->string('companyid');
            $table->string('departmentid');
            $table->string('countadminflg');
            $table->string('depadminflg');
            $table->string('compadminflg');
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
        Schema::dropIfExists('enrolleds');
    }
}
