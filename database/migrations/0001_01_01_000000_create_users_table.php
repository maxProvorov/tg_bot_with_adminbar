<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname');
            $table->string('phone');
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->unsignedBigInteger('telegram_id')->unique();
            $table->timestamps();
        });

        DB::table('users')->insert([
            ['nickname' => 'qwe', 'phone' => '123456987', 'telegram_id'=>'787897879879'],
            ['nickname' => 'qwe123', 'phone' => '123456123', 'telegram_id'=>'487892253235259'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
