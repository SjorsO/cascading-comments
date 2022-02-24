<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('release_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('index');
            $table->unsignedSmallInteger('zip_index');
            $table->string('file_path');
            $table->tinyInteger('type');
            $table->unsignedMediumInteger('starts_at_line_number');
            $table->tinyInteger('number_of_lines');
            $table->boolean('is_perfect');
            $table->string('text', 2000);
            $table->timestamps();

            $table->unique(['release_id', 'index']);
            $table->unique(['release_id', 'zip_index', 'starts_at_line_number'], 'release-index-line');
        });
    }
};
