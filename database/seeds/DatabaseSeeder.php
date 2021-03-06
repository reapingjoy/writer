<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Some user interactions and artisan commands to setup the database
        if($this->command->confirm('Do you want to refresh the database?')) {
            $this->command->call('migrate:refresh');
            $this->command->call('passport:install');
            $this->command->info('Database was refreshed!');
        }

        //Call seeders
        $this->call([
            UsersTableSeeder::class,
            DraftsTableSeeder::class,
            NotesTableSeeder::class,
        ]);
    }
}
