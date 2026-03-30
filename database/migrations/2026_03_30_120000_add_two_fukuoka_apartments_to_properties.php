<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const LAT = '33.58338166353338';

    private const LNG = '130.400754045393';

    private const TITLES = [
        'New apartment — 25 sqm (1)',
        'New apartment — 25 sqm (2)',
    ];

    public function up(): void
    {
        foreach (self::TITLES as $title) {
            $exists = DB::table('properties')
                ->where('title', $title)
                ->where('lat', self::LAT)
                ->where('lng', self::LNG)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('properties')->insert([
                'title' => $title,
                'price' => 'TBD',
                'size' => 25,
                'lat' => self::LAT,
                'lng' => self::LNG,
                'building' => 'Fukuoka',
                'description' => 'New 25 sqm investment apartment. Update price and building details in the admin panel.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('properties')
            ->whereIn('title', self::TITLES)
            ->where('lat', self::LAT)
            ->where('lng', self::LNG)
            ->delete();
    }
};
