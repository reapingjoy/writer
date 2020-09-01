<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Draft;

class DraftsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $draftsCount = (int)$this->command->ask('How many drafts would you like?, 50');
        $users = User::all();
        factory(Draft::class, $draftsCount)->make()->each(function($draft) use ($users) {
            $draft->user_id = $users->random()->id;
            $draft->save();
        });
    }
}
