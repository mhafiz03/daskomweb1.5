<?php

namespace Database\Seeders;

use App\Models\Asisten;
use App\Models\Kelas;
use App\Models\Modul;
use App\Models\Praktikan;
use App\Models\Role;
use App\Models\SoalFitb;
use App\Models\SoalJurnal;
use App\Models\SoalMandiri;
use App\Models\SoalTa;
use App\Models\SoalTk;
use App\Models\SoalTp;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = Role::factory()->count(8)->create();
        $kelas = Kelas::factory()->count(6)->create();
        $moduls = Modul::factory()->count(6)->create();

        Asisten::factory()->count(15)->recycle($roles)->create();
        Praktikan::factory()->count(30)->recycle($kelas)->create();

        SoalFitb::factory()->count(25)->recycle($moduls)->create();
        SoalJurnal::factory()->count(25)->recycle($moduls)->create();
        SoalMandiri::factory()->count(25)->recycle($moduls)->create();
        SoalTa::factory()->count(25)->recycle($moduls)->create();
        SoalTk::factory()->count(25)->recycle($moduls)->create();
        SoalTp::factory()->count(25)->state(['isEssay' => true])->recycle($moduls)->create();
        SoalTp::factory()->count(25)->state(['isProgram' => true])->recycle($moduls)->create();
    }
}
