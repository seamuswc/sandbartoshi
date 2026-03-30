<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Remove property row(s) at this pin (not present in code — lives in DB only).
     * Uses a tiny epsilon so DECIMAL / driver rounding still matches.
     */
    public function up(): void
    {
        $lat = 33.58433341785835;
        $lng = 130.41700818807024;

        DB::table('properties')
            ->whereRaw('ABS(lat - ?) < 1e-6', [$lat])
            ->whereRaw('ABS(lng - ?) < 1e-6', [$lng])
            ->delete();
    }

    /**
     * Row(s) are not restored on rollback.
     */
    public function down(): void
    {
        //
    }
};
