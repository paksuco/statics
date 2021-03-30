<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statics_categories', function (Blueprint $table) {
            $table->after("order", function ($table) {
                $table->boolean("is_deletable")->default(true);
            });
        });

        Schema::table('statics_items', function (Blueprint $table) {
            $table->after("visits", function ($table2) {
                $table2->boolean("is_deletable")->default(true);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
