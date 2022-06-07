<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Thumbs;
use App\Models\Comments;
use DB;
use Share;
use Cviebrock\EloquentSluggable\Services\SlugService;

class PostsController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('blog.index')
            ->with('posts', Post::select('posts.*',DB::raw('COUNT(CASE WHEN thumbs.type = 1 Then thumbs.type else NULL end ) as thumbs_up'),DB::raw('COUNT(CASE WHEN thumbs.type = 2 Then thumbs.type else NULL end ) as thumbs_down'))->leftjoin('thumbs','thumbs.post_id','=','posts.id')->orderBy('updated_at', 'DESC')->groupBy('posts.id')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog.create');
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
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:5048',
            'audio' => 'mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'
        ]);

        if($request->hasFile('audio') != NULL){
            $newAudioName = uniqid() . '-' . $request->title . '.' . $request->audio->extension();
            $request->audio->move(public_path('audio'), $newAudioName);
        }else{
            $newAudioName = "";
        }

        $newImageName = uniqid() . '-' . $request->title . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $newImageName);


        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'slug' => SlugService::createSlug(Post::class, 'slug', $request->title),
            'image_path' => $newImageName,
            'audio_path' => $newAudioName,
            'user_id' => auth()->user()->id
        ]);

        return redirect('/blog')
            ->with('message', 'Your post has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
               // Share button 1
        $shareButtons = \Share::page(
            'http://localhost:8000/blog'.$slug
        )
        ->facebook()
        ->twitter()
        ->linkedin()
        ->telegram()
        ->whatsapp() 
        ->reddit();

        $posts = Post::where('slug',$slug)->first();
        $id = $posts->id;
        return view('blog.show')
            ->with('post', Post::where('slug', $slug)->first())
            ->with('comments', Comments::select('comments.id AS comments_id','comments.*','users.*')->join('users','users.id','=','comments.user_id')->where('post_id',$id)->where('sub_comment',NULL)->get())
            ->with('sub_comments', Comments::select('comments.id AS comments_id','comments.*','users.*')->join('users','users.id','=','comments.user_id')->where('post_id',$id)->whereNotNull('sub_comment')->get())
            ->with('shareButtons',$shareButtons);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        return view('blog.edit')
            ->with('post', Post::where('slug', $slug)->first());
    }

    public function thumbs_up($id){
        $stat = "thumbs";
        $data = Thumbs::where('user_id', auth()->user()->id)
        ->where('post_id',$id)->get();

        foreach($data as $check){
            if($check->type == 2){
                $stat = "change";
            }else{
                $stat = "un_thumbs";
            }
        }
        

        if($stat == "thumbs"){
            Thumbs::create([
                'user_id' => auth()->user()->id,
                'post_id' => $id,
                'type' => 1,
            ]);
    
        }else if($stat == "change"){
            $thumbs = Thumbs::where('user_id', auth()->user()->id)
            ->where('post_id',$id);
            $thumbs->delete();
            Thumbs::create([
                'user_id' => auth()->user()->id,
                'post_id' => $id,
                'type' => 1,
            ]);   
        }
        else{
            $thumbs = Thumbs::where('user_id', auth()->user()->id)
            ->where('post_id',$id);
            $thumbs->delete();    
        }  
        
        return redirect('/blog');
    }

    public function thumbs_down($id){
        $stat = "thumbs";
        $data = Thumbs::where('user_id', auth()->user()->id)
        ->where('post_id',$id)->get();

        foreach($data as $check){
            if($check->type == 1){
                $stat = "change";
            }else{
                $stat = "un_thumbs";
            }
        }
        

        if($stat == "thumbs"){
            Thumbs::create([
                'user_id' => auth()->user()->id,
                'post_id' => $id,
                'type' => 2,
            ]);
    
        }else if($stat == "change"){
            $thumbs = Thumbs::where('user_id', auth()->user()->id)
            ->where('post_id',$id);
            $thumbs->delete();
            Thumbs::create([
                'user_id' => auth()->user()->id,
                'post_id' => $id,
                'type' => 2,
            ]);   
        }
        else{
            $thumbs = Thumbs::where('user_id', auth()->user()->id)
            ->where('post_id',$id);
            $thumbs->delete();    
        }        

        return redirect('/blog');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        

        Post::where('slug', $slug)
            ->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'slug' => SlugService::createSlug(Post::class, 'slug', $request->title),
                'user_id' => auth()->user()->id
            ]);

        return redirect('/blog')
            ->with('message', 'Your post has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $post = Post::where('slug', $slug);
        $post->delete();

        return redirect('/blog')
            ->with('message', 'Your post has been deleted!');
    }
}

