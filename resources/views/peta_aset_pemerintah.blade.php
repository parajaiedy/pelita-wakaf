@extends('peta_layout')

@section('title', 'Peta Aset Pemerintah - Pelita Aset Parepare')

@section('category_badge')
    <div class="category-header aset"><i class="fa-solid fa-building-columns"></i> Aset Pemerintah</div>
@endsection

@section('scripts')
    @include('peta_script', ['data' => $asets])
@endsection
