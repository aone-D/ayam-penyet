@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard</h4>
                </div>
                <div class="card-body text-center">
                    <h2>Selamat Datang di Awan Penyet App</h2>
                    <p class="lead mt-3">Kelola bahan baku dan inventaris Anda dengan mudah</p>
                    
                    <div class="mt-5">
                        <a href="{{ route('bahan-baku.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-box"></i> Kelola Bahan Baku
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection