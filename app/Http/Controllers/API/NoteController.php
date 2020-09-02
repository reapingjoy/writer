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
    public function index($draft_id)
    {
        
        $draft = Draft::findOrFail($draft_id);
        $notes = $draft->notes;

        return NoteResource::collection($notes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $draft_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $note = new Note([
            'title' => $request->title,
            'body' => $request->body,
            'draft_id' => $draft_id,
        ]);
        $note->save();

        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($draft_id, $note_id)
    {
        
        $note = Note::where('id', '=', $note_id)->where('draft_id', '=', $draft_id)->get()->first();

        if (is_null($note)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $this->authorize('view', $note);

        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $draft_id, $note_id)
    {
        $note = Note::where('id', '=', $note_id)->where('draft_id', '=', $draft_id)->get()->first();

        if (is_null($note)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $this->authorize('update', $note);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $note->title = $request->input('title');
        $note->body = $request->input('body');

        $note->save();

        return new NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($draft_id, $note_id)
    {
        $note = Note::where('id', '=', $note_id)->where('draft_id', '=', $draft_id)->get()->first();

        if (is_null($note)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $this->authorize('delete', $note);

        $note->delete();

        return new NoteResource($note);
    }

    /**
     * Get All Soft Deleted Notes
     *
     */
    public function getAllDeleted($draft_id)
    {
        $notes = Note::where('draft_id', '=', $draft_id)->onlyTrashed()->get();

        return NoteResource::collection($notes);
    }
}
