<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $sqls = scandir(__DIR__ . '/../inserts');
        foreach ($sqls as $file) {
            $idx = explode('.', $file);
            $count_explode = count($idx);
            $idx = strtolower($idx[$count_explode - 1]);
            if ($idx === 'sql') {
                $sql = __DIR__ . '/../inserts/' . $file;
                DB::unprepared(file_get_contents($sql));
            }

        }
    }
}
