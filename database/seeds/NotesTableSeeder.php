<?php

use Illuminate\Database\Seeder;
use App\Draft;
use App\Note;
use PhpParser\Node\Stmt\Nop;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drafts = Draft::all();

        if($drafts->count() === 0) {
            $this->command->info('There are no drafts, so no notes will be added!');
            return;
        }
        $notesCount = (int)$this->command->ask('How many notes would you like?, 150');

        factory(Note::class, $notesCount)->make()->each(function($note) use ($drafts) {
            $note->draft_id = $drafts->random()->id;
            $note->save();
        });
    }
}
