<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('location');

            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('application_deadline');

            $table->enum('status', [
                'published',
                'cancelled',
                'completed',
            ])->default('published');

            $table->timestamps();

            $table->index('status');
            $table->index('location');
            $table->index('application_deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};