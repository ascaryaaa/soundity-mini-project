<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;
use DB;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);
        $sub_comment = NULL;
        if($request->sub_comment){
            $sub_comment = $request->sub_comment;
        }


        Comments::create([
            'comment' => $request->input('comment'),
            'sub_comment' => $request->input('sub_comment'),
            'user_id' => auth()->user()->id,
            'post_id' => $request->post_id
        ]);

        return redirect('/blog')
            ->with('message', 'Your comment has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function show(Comments $comments)
    {
        //
    }

    public function reply(Request $request)
    {
        $request->validate([
            'comment' => 'required',
            'sub_comment' => 'required',
        ]);

        Comments::create([
            'comment' => $request->input('comment'),
            'sub_comment' => $request->input('sub_comment'),
            'user_id' => auth()->user()->id,
            'post_id' => $request->post_id
        ]);

        return redirect('/blog')
            ->with('message', 'Your reply has been added!');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function edit(Comments $comments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comments $comments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comments $comments)
    {
        //
    }
}
