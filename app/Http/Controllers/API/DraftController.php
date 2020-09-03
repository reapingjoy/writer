<?php

namespace App\Http\Controllers\API;

use App\Draft;
use App\User;
use App\Http\Resources\Draft as DraftResource;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DraftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Draft::class);

        $user_id = Auth::id();
        $drafts = User::findOrFail($user_id)->drafts()->latest()->get();
        return DraftResource::collection($drafts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'alias' => 'required|unique:drafts',
            'short_description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $draft = new Draft([
            'title' => $request->title,
            'alias' => $request->alias,
            'short_description' => $request->short_description,
            'user_id' => $request->user()->id,
        ]);
        $draft->save();

        return new DraftResource($draft);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $draft = Draft::findOrFail($id);

        $this->authorize('view', $draft);

        return new DraftResource($draft);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $draft = Draft::findOrFail($id);

        $this->authorize('update', $draft);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'short_description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $draft->title = $request->input('title');
        $draft->short_description = $request->input('short_description');

        $draft->save();

        return new DraftResource($draft);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $draft = Draft::findOrFail($id);

        $this->authorize('delete', $draft);

        $draft->delete();

        return new DraftResource($draft);
    }

    /**
     * Get All Soft Deleted Drafts
     *
     */
    public function getAllDeleted()
    {
        $drafts = Draft::onlyTrashed()->get();

        return DraftResource::collection($drafts);
    }

    /**
     * Get All Shared Drafts
     *
     */
    public function getShared()
    {
        $user_id = Auth::id();

        $drafts = User::find($user_id)->shared_drafts;

        return DraftResource::collection($drafts);
    }

    /**
     * Share a draft with other users
     *
     */
    public function shareDraft(Request $request, $id)
    {
        $users = $request->input('users');

        $draft = Draft::findOrFail($id);

        $draft->shared_users()->sync($users);

        return response()->json([
            'message' => 'Draft was shared with the selected users!'
        ]);
    }
}
