<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndonesiaRegionsTable extends Migration
{
    public function up()
    {
        Schema::create('indonesia_regions', function (Blueprint $table) {
            $table->string('code', 20)->primary();
            $table->string('name');
            $table->string('postal_code', 6)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);

            // Basic indexes
            $table->index('name');
            $table->index('postal_code');
            $table->index('is_active');
        });

        // Tambahan indexes untuk optimasi query
        // Menggunakan Laravel Schema Builder untuk kompatibilitas universal
        Schema::table('indonesia_regions', function (Blueprint $table) {
            // Index untuk pencarian berdasarkan panjang kode (level administratif)
            $table->index(['code']); // Sudah ada sebagai primary key, tapi untuk memastikan
        });
        
        // Jalankan seeder untuk mengisi data wilayah Indonesia
        $this->seedIndonesiaRegions();
    }
    
    /**
     * Seed data wilayah Indonesia
     */
    protected function seedIndonesiaRegions()
    {
        // Jalankan seeder langsung
        $seeder = new \Database\Seeders\IndonesiaRegionsSeeder();
        $seeder->run();
    }

    public function down()
    {
        Schema::dropIfExists('indonesia_regions');
    }
}
