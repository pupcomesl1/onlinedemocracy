<?php

use Illuminate\Database\Seeder;

class TenantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tenants')->insert([
            ['id' => 1, 'prefix' => 'kirch', 'long_name' => 'European School of Kirchberg'],
            ['id' => 2, 'prefix' => 'mam', 'long_name' => 'European School of Mamer']
        ]);
    }
}
