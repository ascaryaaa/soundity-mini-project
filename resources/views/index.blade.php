@extends('layouts.app')

@section('content')
    <div class="background-image grid grid-cols-1 m-auto">
        <div class="flex text-gray-100 pt-10">
            <div class="m-auto pt-4 pb-16 sm:m-auto w-4/5 block text-center">
                <h1 class="sm:text-white text-5xl uppercase font-bold text-shadow-md pb-14">
                    Welcome to Soundity
                </h1>
                <a 
                    href="/blog"
                    class="text-center bg-gray-50 text-gray-700 py-2 px-4 font-bold text-xl uppercase">
                    Go to Posts
                </a>
            </div>
        </div>
    </div>

    <div class="sm:grid grid-cols-2 gap-20 w-4/5 mx-auto py-15 border-b border-gray-200">
        <div class="m-auto sm:m-auto text-left w-4/5 block">
            <h2 class="text-3xl font-extrabold text-gray-600">
                Share this website to your friends
            </h2>
            
            <input type="text" value="{{ url()->current() }}" id="copyText" readonly>
            <button id="copyBtn">Copy link to clipboard</button>
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
        </div>
    </div>
@endsection