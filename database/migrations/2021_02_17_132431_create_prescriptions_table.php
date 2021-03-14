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
            $table->float('right_spherical')->nullable();
            $table->float('right_cylinder')->nullable();
            $table->float('right_plus')->nullable();
            $table->float('right_axis')->nullable();
            $table->float('right_pupil_distance')->nullable();
            $table->float('left_spherical')->nullable();
            $table->float('left_cylinder')->nullable();
            $table->float('left_plus')->nullable();
            $table->float('left_axis')->nullable();
            $table->float('left_pupil_distance')->nullable();

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
