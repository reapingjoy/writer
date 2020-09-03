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
    // Get all Drafts for the logged user
    public function index()
    {
        //Check the Draft Policy and authorize the user
        $this->authorize('viewAny', Draft::class);

        $user_id = Auth::id();

        //Get all Drafts
        $drafts = User::findOrFail($user_id)->drafts()->latest()->get();

        //Return the Draft Resource
        return DraftResource::collection($drafts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // Create new draft
    public function store(Request $request)
    {
        //Validate the input
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'alias' => 'required|unique:drafts',
            'short_description' => 'required',
        ]);

        //Return errors from the validator
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //Create new Draft instance
        $draft = new Draft([
            'title' => $request->title,
            'alias' => $request->alias,
            'short_description' => $request->short_description,
            'user_id' => $request->user()->id,
        ]);
        $draft->save();
        
        //Return the Draft Resource
        return new DraftResource($draft);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Show a single draft
    public function show($id)
    {
        //Get a single draft by its id
        $draft = Draft::findOrFail($id);

        //check the policy
        $this->authorize('view', $draft);

        //return resource
        return new DraftResource($draft);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Update a single draft
    public function update(Request $request, $id)
    {
        //get the draft
        $draft = Draft::findOrFail($id);

        //check policy
        $this->authorize('update', $draft);

        //validate input
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'short_description' => 'required',
        ]);
        
        //send errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $draft->title = $request->input('title');
        $draft->short_description = $request->input('short_description');

        $draft->save();

        //return resource
        return new DraftResource($draft);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Soft Delete a single draft
    public function destroy($id)
    {
        //get draft
        $draft = Draft::findOrFail($id);

        //check policy
        $this->authorize('delete', $draft);

        //destroy
        $draft->delete();

        //return deleted resource
        return new DraftResource($draft);
    }

    /**
     * Get All Soft Deleted Drafts
     *
     */
    //List Soft Deleted Drafts
    public function getAllDeleted()
    {
        //Get only trashed
        $drafts = Draft::onlyTrashed()->get();

        //return Resource
        return DraftResource::collection($drafts);
    }

    /**
     * Get All Shared Drafts
     *
     */
    public function getShared()
    {
        //Get user_id
        $user_id = Auth::id();

        //Get all Draftes shared with that user
        $drafts = User::find($user_id)->shared_drafts;

        //return resource
        return DraftResource::collection($drafts);
    }

    /**
     * Share a draft with other users
     *
     */
    public function shareDraft(Request $request, $id)
    {
        //Get the users array
        $users = $request->input('users');

        //get the draft
        $draft = Draft::findOrFail($id);

        //write the users in draft_user table
        $draft->shared_users()->sync($users);
        
        //send response back
        return response()->json([
            'message' => 'Draft was shared with the selected users!'
        ]);
    }
}
