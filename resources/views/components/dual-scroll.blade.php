@extends('users.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dual-scroll.css') }}">
@endsection

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Dual Scroll Layout</h1>

    <div class="dual-scroll-container">
        <div class="vertical-scroll-container">
            @foreach($categories as $category)
                <div class="vertical-item" data-category="{{ $category->id }}">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>

        <div class="horizontal-scroll-container">
            @foreach($categories as $category)
                <div class="scroll-card" data-category="{{ $category->id }}">
                    <h3>{{ $category->name }}</h3>
                    <p>{{ $category->description }}</p>
                    
                    <div class="card-items">
                        @foreach($category->items as $item)
                            <div class="card-item">
                                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                                <h4>{{ $item->name }}</h4>
                                <p>{{ $item->description }}</p>
                                <span class="price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/dual-scroll.js') }}"></script>
@endsection 