@extends('layout')
@section('content')
    <div class="container">
        <h2>{{ $offer->title }} <a target="_blank" style="font-size: 14px;"
                                   href="http://avito.ru{{$offer->grabbedLink->href}}">оригинал</a></h2>
        @if($offer->phones)
            <p>
                <strong>Телефоны:</strong>
                @foreach($offer->phones as $phone)
                    <span>{{ $phone->id }}</span>
                @endforeach
            </p>
        @endif
        <p>{{ $offer->description }}</p>
        <button onclick="$('#raw').slideDown(); $(this).hide();">raw</button>
        <pre id="raw" style="display: none;">{{ var_dump($offer->toArray()) }}</pre>
    </div>
@endsection