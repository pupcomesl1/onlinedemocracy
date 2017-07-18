<?php

use Illuminate\Database\Seeder;

class Initial extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('flag_categories')->insert([
            ['id' => 1, 'name' => 'offensive'],
            ['id' => 2, 'name' => 'inappropriate'],
            ['id' => 3, 'name' => 'incomprehensible']
        ]);

        DB::table('markers')->insert([
            ['id' => 1, 'decode' => 'success'],
            ['id' => 2, 'decode' => 'under_discussion'],
            ['id' => 3, 'decode' => 'no_further_discussion']
        ]);

        DB::table('statuses')->insert([
            ['statusId' => 1, 'decode' => 'accepted'],
            ['statusId' => 2, 'decode' => 'pending'],
            ['statusId' => 3, 'decode' => 'blocked']
        ]);
    }
}
