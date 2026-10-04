@extends('peta_layout')

@section('title', 'Peta Aset Wakaf - Pelita Aset Parepare')

@section('category_badge')
    <div class="category-header wakaf"><i class="fa-solid fa-hand-holding-heart"></i> Aset Wakaf</div>
@endsection

@section('scripts')
    @include('peta_script', ['data' => $asets])
@endsection
