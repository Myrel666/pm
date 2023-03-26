@extends('layouts.app')

@section('title', 'Montern')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
@endpush
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Presensi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.presensi') }}">Presensi</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail Presensi</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleFormControlInput3" class="form-label">Bukti Masuk :</label>
                                <br>
                                <a href="{{ asset('uploads/absensi')}}/{{ $presensi->bukti_masuk }}" data-fancybox
                                    data-caption="Foto" data-width="1300" data-height="1000">
                                    <i class="bi bi-card-image"></i>
                                    Lihat Gambar
                                </a>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput3" class="form-label">Bukti Pulang :</label>
                                <br>
                                <a href="{{ asset('uploads/absensi')}}/{{ $presensi->bukti_pulang }}" data-fancybox
                                    data-caption="Foto" data-width="1300" data-height="1000">
                                    <i class="bi bi-card-image"></i>
                                    Lihat Gambar
                                </a>
                            </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput3" class="form-label">Lokasi Masuk</label>
                                    <br>
                                    <div id="mapmasuk" style="height: 500px" data-long="{{ $presensi->longitude_masuk }}" data-lat="{{ $presensi->latitude_masuk }}"></div> 
                                </div> 
                                <div class="mb-3">
                                    <label for="exampleFormControlInput3" class="form-label">Lokasi Pulang</label>
                                    <br>
                                    <div id="mappulang" style="height: 500px" data-long="{{ $presensi->longitude_pulang }}" data-lat="{{ $presensi->latitude_pulang }}"></div> 
                                </div> 
                            <div class="d-flex justify-content-end">
                                <a href="{{ url()->previous() }}" class="btn btn-info btn-sm float-end px-4">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
    var longmasuk= document.getElementById('mapmasuk').getAttribute('data-long');
    var latmasuk = document.getElementById('mapmasuk').getAttribute('data-lat');

    var longpulang = document.getElementById('mappulang').getAttribute('data-long');
    var latpulang = document.getElementById('mappulang').getAttribute('data-lat');
    
    
	var mapmasuk = L.map('mapmasuk').setView([longmasuk, latmasuk], 20);
    var mappulang = L.map('mappulang').setView([longpulang, latpulang], 20);

	var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(mapmasuk);

	L.marker([longmasuk, latmasuk]).addTo(mapmasuk);

    var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(mappulang);

	L.marker([longpulang, latmasuk]).addTo(mappulang);
  
</script>
@endpush