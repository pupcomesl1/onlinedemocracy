<?php

use Illuminate\Database\Seeder;

class TestModerator extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User([
            'id' => 0,
            'displayName' => 'TestModerator',
            'msgraphId' => '123',
            'tenant_id' => 1,
            'email' => 'testmod@kirch.dd.dev'
        ]);
        $user->saveOrFail();

        $userRole = \App\Role::where('name', 'user')->get()->first();
        $modRole = \App\Role::where('name', 'moderator')->get()->first();
        $user->attachRole($userRole);
        $user->attachRole($modRole);
    }
}
