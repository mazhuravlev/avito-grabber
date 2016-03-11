@extends('layout')
@section('content')
    <h2>Предложения {{$offers->currentPage()}}/{{$offers->total()}}</h2>
    <div class="container">
        @foreach ($offers as $offer)
            <a href="/offers/{{ $offer->id }}">{{ $offer->title }}</a>
        @endforeach
    </div>

    {!! $offers->links() !!}
@endsection