<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const LAT = 33.58338166353338;

    private const LNG = 130.400754045393;

    private const NEW_TITLES = [
        '東峰マンション薬院（1）',
        '東峰マンション薬院（2）',
    ];

    public function up(): void
    {
        $rows = DB::table('properties')
            ->whereRaw('ABS(lat - ?) < 1e-6', [self::LAT])
            ->whereRaw('ABS(lng - ?) < 1e-6', [self::LNG])
            ->orderBy('id')
            ->get(['id']);

        foreach ($rows as $index => $row) {
            if ($index >= 2) {
                break;
            }

            DB::table('properties')->where('id', $row->id)->update([
                'title' => self::NEW_TITLES[$index],
                'building' => '東峰マンション薬院',
                'updated_at' => now(),
            ]);
        }

        DB::table('properties')
            ->where(function ($q) {
                $q->where('building', 'シティハウス博多')
                    ->orWhere('title', 'like', '%シティハウス博多%');
            })
            ->delete();
    }

    public function down(): void
    {
        //
    }
};
