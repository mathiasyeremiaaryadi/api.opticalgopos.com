<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('right_spherical');
            $table->float('right_cylinder');
            $table->float('right_plus');
            $table->float('right_axis');
            $table->float('right_pupil_distance');
            $table->float('left_spherical');
            $table->float('left_cylinder');
            $table->float('left_plus');
            $table->float('left_axis');
            $table->float('left_pupil_distance');

            $table->foreignId('customers_id')->constrained('customers');

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
        Schema::dropIfExists('prescriptions');
    }
}
