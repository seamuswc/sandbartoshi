<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Match map grouping: same lat/lng shares one pin — number （1）（2）… within each location.
     */
    public function up(): void
    {
        $rows = DB::table('properties')
            ->where(function ($q) {
                $q->where('building', 'like', '%西中洲コーポ%')
                    ->orWhere('title', 'like', '%西中洲コーポ%');
            })
            ->orderBy('id')
            ->get(['id', 'lat', 'lng']);

        $byLocation = [];
        foreach ($rows as $row) {
            $key = (string) $row->lat . ',' . (string) $row->lng;
            $byLocation[$key][] = $row->id;
        }

        foreach ($byLocation as $ids) {
            foreach ($ids as $index => $id) {
                $n = $index + 1;
                DB::table('properties')->where('id', $id)->update([
                    'title' => '西中洲コーポ（' . $n . '）',
                    'building' => '西中洲コーポ',
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
