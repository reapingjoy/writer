<?php

namespace App\Http\Controllers\API;

use App\Draft;
use App\User;
use App\Note;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Note as NoteResource;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //List all notes for specific draft
    public function index($draft_id)
    {
        //get the draft
        $draft = Draft::findOrFail($draft_id);

        //get his notes
        $notes = $draft->notes;

        //return NoteResource
        return NoteResource::collection($notes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //Create new note for a specific draft
    public function store(Request $request, $draft_id)
    {
        //validate the input
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ]);

        //send errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //create new note instance
        $note = new Note([
            'title' => $request->title,
            'body' => $request->body,
            'draft_id' => $draft_id,
        ]);
        $note->save();

        //return resource
        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Show a single note
    public function show($draft_id, $note_id)
    {
        //Check for permission
        $note = Note::where('id', '=', $note_id)->where('draft_id', '=', $draft_id)->get()->first();

        if (is_null($note)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        //check view policy
        $this->authorize('view', $note);

        //return resource
        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Update a single note
    public function update(Request $request, $draft_id, $note_id)
    {
        //Check for permission
        $note = Note::where('id', '=', $note_id)->where('draft_id', '=', $draft_id)->get()->first();

        if (is_null($note)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        //check policy
        $this->authorize('update', $note);

        //validate
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ]);
        
        //send errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //set values
        $note->title = $request->input('title');
        $note->body = $request->input('body');

        $note->save();

        //return resource
        return new NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Delete single note
    public function destroy($draft_id, $note_id)
    {
        //Check for permission
        $note = Note::where('id', '=', $note_id)->where('draft_id', '=', $draft_id)->get()->first();

        if (is_null($note)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        //check policy
        $this->authorize('delete', $note);

        $note->delete();

        //return resource
        return new NoteResource($note);
    }

    /**
     * Get All Soft Deleted Notes
     *
     */
    public function getAllDeleted($draft_id)
    {
        //get only trashed notes
        $notes = Note::where('draft_id', '=', $draft_id)->onlyTrashed()->get();

        //return resource
        return NoteResource::collection($notes);
    }
}
