<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('programme_of_studies')
            ->where(fn ($q) => $q->whereNull('uuid')->orWhere('uuid', ''))
            ->orderBy('id')
            ->each(function ($row) {
                DB::table('programme_of_studies')
                    ->where('id', $row->id)
                    ->update(['uuid' => Str::uuid()->toString()]);
            });
    }

    public function down(): void
    {
        DB::table('programme_of_studies')->update(['uuid' => null]);
    }
};
