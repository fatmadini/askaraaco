@extends('layouts.app')



@section('content')

    <h2>Portfolio</h2>



    @foreach($portfolios as $item)

        <div class="card mb-3">

            <div class="card-body">

                <h4>{{ $item->title }}</h4>

                <p>{{ $item->description }}</p>

            </div>

        </div>

    @endforeach



@endsection