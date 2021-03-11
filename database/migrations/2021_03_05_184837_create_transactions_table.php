<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 20);
            $table->string('lens_type', 100);
            $table->integer('total');
            $table->tinyInteger('status')->length(1);

            $table->foreignId('payments_id')->constrained('payments')->onDelete('RESTRICT');
            $table->foreignId('categories_id')->constrained('categories')->onDelete('RESTRICT');
            $table->foreignId('customers_id')->constrained('customers')->onDelete('RESTRICT');
            
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
        Schema::dropIfExists('transactions');
    }
}
