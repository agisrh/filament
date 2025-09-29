<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IndonesiaRegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if data already exists
        if (DB::table('indonesia_regions')->count() > 0) {
            if ($this->command) {
                $this->command->info('Indonesia regions data already exists. Skipping seeding.');
            }
            return;
        }

        if ($this->command) {
            $this->command->info('Starting Indonesia regions data seeding...');
        }

        $sqlFile = base_path('vendor/aliziodev/laravel-indonesia-regions/src/Database/Sql/indonesia_regions.sql');

        if (!File::exists($sqlFile)) {
            if ($this->command) {
                $this->command->error('Indonesia regions SQL file not found!');
            }
            return;
        }

        try {
            $sql = File::get($sqlFile);
            
            // Remove backticks and fix the SQL
            $sql = str_replace('`indonesia_regions`', 'indonesia_regions', $sql);

            // Extract just the VALUES part
            preg_match('/VALUES\s*(.+)$/s', $sql, $matches);
            if (!isset($matches[1])) {
                if ($this->command) {
                    $this->command->error('Could not parse SQL VALUES');
                }
                return;
            }

            $valuesString = trim($matches[1]);
            $valuesString = rtrim($valuesString, ';');

            // Split by lines and process each value
            $lines = explode("\n", $valuesString);
            $batchSize = 1000;
            $batch = [];
            $totalInserted = 0;

            if ($this->command) {
                $this->command->info('Processing ' . count($lines) . ' lines...');
            }

            foreach ($lines as $lineNumber => $line) {
                $line = trim($line);
                if (empty($line) || $line === ',') continue;
                
                // Remove trailing comma
                $line = rtrim($line, ',');
                
                // Parse the values
                if (preg_match("/\('([^']+)',\s*'([^']*)',\s*(NULL|'[^']*'),\s*([^,]+),\s*([^)]+)\)/", $line, $matches)) {
                    $batch[] = [
                        'code' => $matches[1],
                        'name' => $matches[2],
                        'postal_code' => $matches[3] === 'NULL' ? null : trim($matches[3], "'"),
                        'latitude' => (float) $matches[4],
                        'longitude' => (float) $matches[5],
                        'status' => 'active'
                    ];
                    
                    if (count($batch) >= $batchSize) {
                        DB::table('indonesia_regions')->insert($batch);
                        $totalInserted += count($batch);
                        if ($this->command) {
                            $this->command->info("Inserted $totalInserted records...");
                        }
                        $batch = [];
                    }
                }
            }

            // Insert remaining batch
            if (!empty($batch)) {
                DB::table('indonesia_regions')->insert($batch);
                $totalInserted += count($batch);
            }

            $finalCount = DB::table('indonesia_regions')->count();
            if ($this->command) {
                $this->command->info("Indonesia regions seeding completed!");
                $this->command->info("Total records inserted: $totalInserted");
                $this->command->info("Total records in database: $finalCount");
            }

        } catch (\Exception $e) {
            if ($this->command) {
                $this->command->error('Error seeding Indonesia regions: ' . $e->getMessage());
            }
            throw $e;
        }
    }
}