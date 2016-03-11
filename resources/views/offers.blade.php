@extends('layout')
@section('content')
    <h2>Предложения, страница {{$offers->currentPage()}} из {{$offers->lastPage()}}, всего {{ $offers->total() }}</h2>
    <div class="container">
        @foreach ($offers as $offer)
            <p>
                <a href="/offers/{{ $offer->id }}">{{ $offer->title }}</a>
            </p>
        @endforeach
    </div>

    {!! $offers->links() !!}
@endsection