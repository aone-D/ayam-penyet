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
                    <h2>Selamat Datang di Aplikasi Resep</h2>
                    <p class="lead mt-3">Kelola resep dan bahan-bahan Anda dengan mudah</p>
                    
                    <div class="mt-5">
                        <a href="{{ route('menu.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus"></i> Buat Resep Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection