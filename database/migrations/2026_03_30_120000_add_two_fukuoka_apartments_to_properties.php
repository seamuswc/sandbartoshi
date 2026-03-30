<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const LAT = '33.58338166353338';

    private const LNG = '130.400754045393';

    private const TITLES = [
        '東峰マンション薬院（1）',
        '東峰マンション薬院（2）',
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
                'building' => '東峰マンション薬院',
                'description' => '薬院エリア 25㎡（必要に応じて管理画面で価格・説明を更新してください）',
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
