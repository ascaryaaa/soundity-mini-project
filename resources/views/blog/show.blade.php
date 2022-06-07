    @extends('layouts.app')

    @section('content')
    <div class="w-4/5 m-auto text-left">
        <div class="py-15">
            <h1 class="text-6xl">
                {{ $post->title }}                    
            </h1>
        </div>
    </div>
    <div class="w-4/5 m-auto text-left" style="inline-block">
        {!! $shareButtons !!}
    </div>
    <div syle="background-color:black;">
       
    </div>

    <div class="w-4/5 m-auto pt-20 pb-10">
        <span class="text-gray-500">
            By <span class="font-bold italic text-gray-800">{{ $post->user->name }}</span>, Created on {{ date('jS M Y', strtotime($post->updated_at)) }}
        </span>

        <input type="text" value="{{ url()->current() }}" id="copyText" readonly>
        <button id="copyBtn">Copy link to post</button>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script>
            const copyBtn = document.getElementById('copyBtn')
            const copyText = document.getElementById('copyText')
            
            copyBtn.onclick = () => {
                copyText.select();    // Selects the text inside the input
                document.execCommand('copy');    // Simply copies the selected text to clipboard 
                Swal.fire({         //displays a pop up with sweetalert
                    icon: 'success',
                    title: 'Link copied to clipboard',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        </script>
        
        <p class="text-xl text-gray-700 pt-8 pb-10 leading-8 font-light">
            {{ $post->description }}
        </p>
        @if($post->audio_path !== "")
        <div class="pt-4 pb-10">
            <span class="float-left">
                <audio controls>
                    <source src="{{ asset('audio/' . $post->audio_path) }}" type="audio/mpeg">
                </audio>    
            </span>
            <span class="float-left mt-3">
                <a href="{{ asset('audio/' . $post->audio_path) }}" download><img src="{{ asset('icon/download.png') }}" alt="" width="30px">    </a>
            </span>
        </div>        
    </div>

    @endif
    @foreach ($comments as $comment)
        <div class="w-4/5 m-auto pt-10 mb-3 bg-blue-500 pb-5 text-lg" style="overflow:hidden;">
            <span class="float-left">
                <h1 class="font-extrabold pl-7">{{$comment->name}}</h1>
                <p class="pl-7 pt-3">{{$comment->comment}}</p>
                @foreach ($sub_comments as $sub_comment)
                    @if($sub_comment->sub_comment == $comment->comments_id)
                    <img src="{{ asset('icon/down.png') }}" width="60px" class="pl-6 float-left">
                    <h1 class="font-extrabold pl-7 pt-3">{{$sub_comment->name}}</h1>
                    <p class="pl-15 pt-3">{{$sub_comment->comment}}</p>
                    @endif
                @endforeach
                <h1 class=" pl-7 pt-4 text-white">Reply</h1>
                <form 
                    action="/comments/reply"
                    method="post">
                    @csrf
                    <input type="hidden" name="sub_comment" value="{{ $comment->comments_id }}">
                    <input type="hidden" name="post_id" value={{$post->id}}>
                    <textarea class="mt-5 ml-7 mr-5 pl-2 pt-2" style="width: 1000px; height: 100px;" name="comment"></textarea>   
                    <button type="submit" class="font-extrabold text-white bg-green-500 pr-4 pb-4 pt-4 pl-4 text-lg ml-7 mt-2"> Submit </button> 
                </form>
            </span>
        </div>
    @endforeach

    <div class="w-4/5 m-auto pt-15 text-center bg-gray-500  pb-10">
        <h1 class=" font-extrabold text-white text-lg pt-10">COMMENT</h1>
        <form 
            action="/comments"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="post_id" value={{$post->id}}>
            <textarea class="mt-5" style="width: 900px; height: 300px;" name="comment">

            </textarea>
            <div class="mt-5">
                <button type="submit" class="font-extrabold text-white bg-blue-500 pr-4 pb-4 pt-4 pl-4 text-lg"> Submit </button>
            </div>
        </form>
    </div>

    @endsection 