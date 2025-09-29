<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Organization;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6)->unique();
            $table->string('name', 255);
            $table->string('slug')->unique();
            $table->string('logo', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website', 150)->nullable();
            $table->text('address')->nullable();
            $table->string('region_code', 20)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('region_code')->references('code')->on('indonesia_regions')->onDelete('set null');
        });

        Schema::create('organization_user', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Organization::class);
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('organization_user');
    }
};
