<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id')
                ->constrained('jobs')
                ->cascadeOnDelete();

            $table->foreignId('volunteer_id')
                ->constrained('volunteer_profiles')
                ->cascadeOnDelete();

            $table->string('cv')->nullable();

            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
            ])->default('pending');

            $table->timestamp('applied_at')->useCurrent();

            $table->timestamps();

            // منع المتطوع من التقديم على نفس الوظيفة أكثر من مرة
            $table->unique([
                'job_id',
                'volunteer_id',
            ]);

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};