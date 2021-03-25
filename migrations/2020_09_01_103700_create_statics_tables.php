<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title");
            $table->string("slug");
            $table->text("description")->nullable();
            $table->foreignId("parent_id")->nullable();
            $table->integer("order");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("parent_id")->references("id")->on("static_categories")->cascadeOnDelete();
        });

        Schema::create('static_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('category_id')->nullable();
            $table->string("title", 100);
            $table->string("slug", 100);
            $table->text("excerpt");
            $table->text("content");
            $table->boolean("published")->default(false);
            $table->integer("order");
            $table->integer("likes");
            $table->integer("dislikes");
            $table->integer("visits");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("category_id")->references("id")
                ->on("static_categories")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('static_items');
        Schema::dropIfExists('static_categories');
        Schema::enableForeignKeyConstraints();
    }
}
