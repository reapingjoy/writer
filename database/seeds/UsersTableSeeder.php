<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Some user interactions and using the factory to seed
        $usersCount = max((int)$this->command->ask('How many users would you like?, 20'), 1);
        factory(User::class)->states('nedko')->create();
        factory(User::class, $usersCount)->create();
    }
}
