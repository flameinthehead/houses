<?php

use App\Helpers\ImportHouses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportHousesFromCsv extends Migration
{
    private $importService;

    public function __construct()
    {
        $this->importService = app(ImportHouses::class);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            DB::beginTransaction();
            $this->importService->makeImport();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo 'Import failed with message - '.$e->getMessage();
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('houses')->truncate();
    }
}
