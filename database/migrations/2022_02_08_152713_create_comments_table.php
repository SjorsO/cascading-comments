<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('release_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('zip_index');
            $table->string('file_path');
            $table->unsignedSmallInteger('type');
            $table->unsignedInteger('starts_at_line_number');
            $table->unsignedSmallInteger('number_of_lines');
            $table->boolean('is_perfect');
            $table->text('text');
            $table->timestamps();

            $table->unique(['release_id', 'zip_index', 'starts_at_line_number'], 'release-index-line');
        });
    }
}
