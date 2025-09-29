<?php

namespace App\Helpers;

use Filament\Forms\Components\Select;
use Aliziodev\IndonesiaRegions\Models\IndonesiaRegion;

class IndonesiaRegionHelper
{
    /**
     * Create province select field
     */
    public static function provinceSelect(
        string $name = 'province_code',
        string $label = 'Province',
        array $dependentFields = ['city_code', 'district_code', 'village_code']
    ): Select {
        return Select::make($name)
            ->label($label)
            ->options(function () {
                try {
                    // Mengambil province saja (kode 2 digit) yang aktif
                    return IndonesiaRegion::whereRaw('LENGTH(code) = 2')
                        ->where('is_active', true)
                        ->pluck('name', 'code')
                        ->toArray();
                } catch (\Exception $e) {
                    return [];
                }
            })
            ->searchable()
            ->reactive()
            ->afterStateUpdated(function (callable $set) use ($dependentFields) {
                foreach ($dependentFields as $field) {
                    $set($field, null);
                }
            });
    }

    /**
     * Create city select field
     */
    public static function citySelect(
        string $name = 'city_code',
        string $label = 'City',
        string $provinceField = 'province_code',
        array $dependentFields = ['district_code', 'village_code']
    ): Select {
        return Select::make($name)
            ->label($label)
            ->options(function (callable $get) use ($provinceField) {
                $provinceCode = $get($provinceField);
                if (!$provinceCode) {
                    return [];
                }
                
                try {
                    // Mengambil city berdasarkan province code (kode 5 digit yang dimulai dengan province code) yang aktif
                    return IndonesiaRegion::whereRaw('LENGTH(code) = 5')
                        ->where('code', 'like', $provinceCode . '.%')
                        ->where('is_active', true)
                        ->pluck('name', 'code')
                        ->toArray();
                } catch (\Exception $e) {
                    return [];
                }
            })
            ->searchable()
            ->reactive()
            ->disabled(fn (callable $get) => !$get($provinceField))
            ->dehydrated(fn (callable $get) => (bool) $get($provinceField))
            ->afterStateUpdated(function (callable $set) use ($dependentFields) {
                foreach ($dependentFields as $field) {
                    $set($field, null);
                }
            });
    }

    /**
     * Create district select field
     */
    public static function districtSelect(
        string $name = 'district_code',
        string $label = 'District',
        string $cityField = 'city_code',
        array $dependentFields = ['village_code']
    ): Select {
        return Select::make($name)
            ->label($label)
            ->options(function (callable $get) use ($cityField) {
                $cityCode = $get($cityField);
                if (!$cityCode) {
                    return [];
                }
                
                try {
                    // Mengambil district berdasarkan city code (kode 8 digit yang dimulai dengan city code) yang aktif
                    return IndonesiaRegion::whereRaw('LENGTH(code) = 8')
                        ->where('code', 'like', $cityCode . '.%')
                        ->where('is_active', true)
                        ->pluck('name', 'code')
                        ->toArray();
                } catch (\Exception $e) {
                    return [];
                }
            })
            ->searchable()
            ->reactive()
            ->disabled(fn (callable $get) => !$get($cityField))
            ->dehydrated(fn (callable $get) => (bool) $get($cityField))
            ->afterStateUpdated(function (callable $set) use ($dependentFields) {
                foreach ($dependentFields as $field) {
                    $set($field, null);
                }
            });
    }

    /**
     * Create village select field
     */
    public static function villageSelect(
        string $name = 'village_code',
        string $label = 'Village',
        string $districtField = 'district_code'
    ): Select {
        return Select::make($name)
            ->label($label)
            ->options(function (callable $get) use ($districtField) {
                $districtCode = $get($districtField);
                if (!$districtCode) {
                    return [];
                }
                
                try {
                    // Mengambil village berdasarkan district code (kode > 8 digit yang dimulai dengan district code) yang aktif
                    return IndonesiaRegion::whereRaw('LENGTH(code) > 8')
                        ->where('code', 'like', $districtCode . '.%')
                        ->where('is_active', true)
                        ->pluck('name', 'code')
                        ->toArray();
                } catch (\Exception $e) {
                    return [];
                }
            })
            ->searchable()
            ->reactive()
            ->disabled(fn (callable $get) => !$get($districtField))
            ->dehydrated(fn (callable $get) => (bool) $get($districtField));
    }

    /**
     * Create complete Indonesia region fields (Province, City, District, Village)
     */
    public static function completeRegionFields(
        array $fieldNames = [
            'province' => 'province_code',
            'city' => 'city_code',
            'district' => 'district_code',
            'village' => 'village_code'
        ],
        array $labels = [
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'village' => 'Village'
        ]
    ): array {
        return [
            self::provinceSelect(
                $fieldNames['province'],
                $labels['province'],
                [$fieldNames['city'], $fieldNames['district'], $fieldNames['village']]
            ),
            self::citySelect(
                $fieldNames['city'],
                $labels['city'],
                $fieldNames['province'],
                [$fieldNames['district'], $fieldNames['village']]
            ),
            self::districtSelect(
                $fieldNames['district'],
                $labels['district'],
                $fieldNames['city'],
                [$fieldNames['village']]
            ),
            self::villageSelect(
                $fieldNames['village'],
                $labels['village'],
                $fieldNames['district']
            ),
        ];
    }

    /**
     * Get active provinces
     */
    public static function getActiveProvinces(): array
    {
        try {
            return IndonesiaRegion::whereRaw('LENGTH(code) = 2')
                ->where('is_active', true)
                ->pluck('name', 'code')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get active cities by province code
     */
    public static function getActiveCities(string $provinceCode): array
    {
        try {
            return IndonesiaRegion::whereRaw('LENGTH(code) = 5')
                ->where('code', 'like', $provinceCode . '.%')
                ->where('is_active', true)
                ->pluck('name', 'code')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get active districts by city code
     */
    public static function getActiveDistricts(string $cityCode): array
    {
        try {
            return IndonesiaRegion::whereRaw('LENGTH(code) = 8')
                ->where('code', 'like', $cityCode . '.%')
                ->where('is_active', true)
                ->pluck('name', 'code')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get active villages by district code
     */
    public static function getActiveVillages(string $districtCode): array
    {
        try {
            return IndonesiaRegion::whereRaw('LENGTH(code) > 8')
                ->where('code', 'like', $districtCode . '.%')
                ->where('is_active', true)
                ->pluck('name', 'code')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get region by code (only active)
     */
    public static function getActiveRegionByCode(string $code): ?IndonesiaRegion
    {
        try {
            return IndonesiaRegion::where('code', $code)
                ->where('is_active', true)
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if region is active
     */
    public static function isRegionActive(string $code): bool
    {
        try {
            return IndonesiaRegion::where('code', $code)
                ->where('is_active', true)
                ->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get region hierarchy (province -> city -> district -> village) for active regions only
     */
    public static function getActiveRegionHierarchy(string $code): array
    {
        try {
            $region = self::getActiveRegionByCode($code);
            if (!$region) {
                return [];
            }

            $hierarchy = [];
            $codeLength = strlen($code);

            // Determine region type based on code length
            if ($codeLength == 2) {
                // Province
                $hierarchy['province'] = $region;
            } elseif ($codeLength == 5) {
                // City
                $provinceCode = substr($code, 0, 2);
                $hierarchy['province'] = self::getActiveRegionByCode($provinceCode);
                $hierarchy['city'] = $region;
            } elseif ($codeLength == 8) {
                // District
                $provinceCode = substr($code, 0, 2);
                $cityCode = substr($code, 0, 5);
                $hierarchy['province'] = self::getActiveRegionByCode($provinceCode);
                $hierarchy['city'] = self::getActiveRegionByCode($cityCode);
                $hierarchy['district'] = $region;
            } else {
                // Village
                $provinceCode = substr($code, 0, 2);
                $cityCode = substr($code, 0, 5);
                $districtCode = substr($code, 0, 8);
                $hierarchy['province'] = self::getActiveRegionByCode($provinceCode);
                $hierarchy['city'] = self::getActiveRegionByCode($cityCode);
                $hierarchy['district'] = self::getActiveRegionByCode($districtCode);
                $hierarchy['village'] = $region;
            }

            return array_filter($hierarchy); // Remove null values
        } catch (\Exception $e) {
            return [];
        }
    }
}