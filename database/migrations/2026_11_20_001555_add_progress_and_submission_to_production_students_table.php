<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('production_students', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress')
                ->default(0)
                ->after('status');

            $table->string('submission_file')
                ->nullable()
                ->after('progress');

            $table->text('work_notes')
                ->nullable()
                ->after('submission_file');
        });
    }

    public function down(): void
    {
        Schema::table('production_students', function (Blueprint $table) {
            $table->dropColumn([
                'progress',
                'submission_file',
                'work_notes',
            ]);
        });
    }
};
