<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('employee');
        });
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('customer');
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });
        Schema::create('investigations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained();
            $table->text('summary');
            $table->timestamps();
        });
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('driver');
            $table->double('latitude');
            $table->double('longitude');
            $table->timestamps();
        });
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('journalist');
            $table->dateTime('deadline')->index();
            $table->timestamps();
        });
        Schema::create('issue_reports', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('location');
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('interest');
            $table->string('major');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['recommendations', 'issue_reports', 'articles', 'buses', 'investigations', 'transactions'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
    }
};
