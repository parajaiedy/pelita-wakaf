@extends('peta_layout')

@section('title', 'Peta Gabungan Aset - Pelita Aset Parepare')

@section('category_badge')
    <div class="category-header gabungan"><i class="fa-solid fa-layer-group"></i> Peta Gabungan Semua Aset</div>
@endsection

@section('scripts')
    @include('peta_script', ['data' => $asets])
@endsection
