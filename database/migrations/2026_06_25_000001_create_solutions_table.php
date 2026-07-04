<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('tagline');
            $table->text('summary');
            $table->text('description')->nullable();
            $table->string('icon')->default('sparkles');
            $table->string('badge')->nullable();
            $table->string('status')->default('active'); // active | soon
            $table->boolean('is_flagship')->default(false);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->json('includes')->nullable();  // ["Página profesional", ...]
            $table->json('premium')->nullable();    // optional premium modules
            $table->json('features')->nullable();   // [{icon,title,text}] for detail page
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
