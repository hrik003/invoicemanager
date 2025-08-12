<?php
// database/migrations/2025_08_11_000004_create_settings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            ['key' => 'invoice_seq', 'value' => '0'],
            ['key' => 'invoice_prefix', 'value' => 'INV']
        ]);
    }
    public function down() {
        Schema::dropIfExists('settings');
    }
};
