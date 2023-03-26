@extends('layouts.app')

@section('title', 'Montern')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
@endpush
@section('content')
@inject('carbon', 'Carbon\Carbon')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Log Presensi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('user.beranda') }}">Home</a></li>
                        <li class="breadcrumb-item active">Log Presensi</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel Log Presensi</h3>
                            <div class="card-tools">
                            <?php if(isset($_GET['bulan'])) {
                                $bulan = $_GET['bulan'];
                              } else {
                                $bulan = date("m"); //build in function
                              }?>
                                <select class="form-control form-control-sm" onchange="window.location='?bulan='+this.value">
                                  <option value="1" <?= $bulan == 1 ? 'selected' : '' ;?>>Jan</option>
                                  <option value="2" <?= $bulan == 2 ? 'selected' : '' ;?>>Feb</option>
                                  <option value="3" <?= $bulan == 3 ? 'selected' : '' ;?>>Mar</option>
                                  <option value="4" <?= $bulan == 4 ? 'selected' : '' ;?>>Apr</option>
                                  <option value="5" <?= $bulan == 5 ? 'selected' : '' ;?>>May</option>
                                  <option value="6" <?= $bulan == 6 ? 'selected' : '' ;?>>Jun</option>
                                  <option value="7" <?= $bulan == 7 ? 'selected' : '' ;?>>Jul</option>
                                  <option value="8" <?= $bulan == 8 ? 'selected' : '' ;?>>Aug</option>
                                  <option value="9" <?= $bulan == 9 ? 'selected' : '' ;?>>Sep</option>
                                  <option value="10" <?= $bulan == 10 ? 'selected' : '' ;?>>Oct</option>
                                  <option value="11" <?= $bulan == 11 ? 'selected' : '' ;?>>Nov</option>
                                  <option value="12" <?= $bulan == 12 ? 'selected' : '' ;?>>Dec</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                        @php
                        $bulan = (!isset($_GET['bulan'])) ? date("m") : $_GET['bulan'];
                        function last_day_of_the_month($date = '')
                        {
                          $month  = date('m', strtotime($date));
                          $year   = date('Y', strtotime($date));
                          $result = strtotime("{$year}-{$month}-01");
                          $result = strtotime('-1 second', strtotime('+1 month', $result));
                          return date('d', $result);
                        }
                            $jam_absen_pulang = 17;
                            $dataPresensi = [];
                            $dataPengajuan = [];
                            $status = 'A';
                            $last = \Carbon\Carbon::parse(date('Y-') . "$bulan")->daysInMonth; //2022-10
                            @endphp

                            @if(!$presensi->isEmpty())
                            @foreach($presensi as $absen)
                            @php
                            $dataPresensi[$absen['tgl']] = $absen;
                            @endphp
                            @endforeach
                            @endif


                            @foreach($pengajuan as $ajuan)
                            @php
                            $dataPengajuan[$ajuan['created_at']->format('Y-m-d')] = $ajuan;
                            @endphp
                            @endforeach
                            <table id="logTable" class="table table-bordered table-striped table-responsive-md"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 1; $i <= $last; $i++) 
                                    @php 
                                    $tgl=\Carbon\Carbon::createFromDate(today()->
                                        year, $bulan, $i)->format('Y-m-d')
                                    @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ \Carbon\Carbon::createFromDate(today()->year, $bulan, $i)->format('j M Y'); }}
                                            </td>
                                            <td>
                                                @isset($dataPresensi[$tgl])
                                                @if($dataPresensi[$tgl]->bukti_masuk == null)
                                                -
                                                @else
                                                <a href="{{ asset('uploads/absensi')}}/{{ $dataPresensi[$tgl]->bukti_masuk }}"
                                                    data-fancybox=data-caption="Optional caption" data-width="1300"
                                                    data-height="1000">{{ \Carbon\Carbon::parse($dataPresensi[$tgl]->created_at)->format('H:i:s')}}</a>
                                                @endif
                                                @endisset
                                                @if(empty($dataPresensi[$tgl]))
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @isset($dataPresensi[$tgl])
                                                @if($dataPresensi[$tgl]->bukti_pulang == null)
                                                -
                                                @else
                                                <a href="{{ asset('uploads/absensi')}}/{{ $dataPresensi[$tgl]->bukti_pulang }}"
                                                    data-fancybox=data-caption="Optional caption" data-width="1300"
                                                    data-height="1000">{{ 
                                                        \Carbon\Carbon::parse($dataPresensi[$tgl]->updated_at)->format('H:i:s')}}</a>
                                                @endif
                                                @endisset
                                                @if(empty($dataPresensi[$tgl]))
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($dataPresensi[$tgl]))
                                                @php

                                                $masuk = $dataPresensi[$tgl]->created_at == null ? '00:00:00' :
                                                date('H:i:s',strtotime($dataPresensi[$tgl]->created_at));

                                                $pulang = $dataPresensi[$tgl]->created_at == null ? '00:00:00' :
                                                date('H:i:s',strtotime($dataPresensi[$tgl]->updated_at));
                                                
                                                if($pulang >= '17:00:00' && $masuk <= '08:00:00' ){ 
                                                    $status= 'Hadir';
                                                }else if(($dataPresensi[$tgl]->bukti_pulang != null && $dataPresensi[$tgl]->bukti_masuk != null) && ($pulang >= '17:00:00' || $masuk > '08:00:00')){
                                                    $status = 'Hadir Terlambat';
                                                }else if($dataPresensi[$tgl]->bukti_pulang == null){
                                                    $status = 'Hadir Tanpa Absen Pulang';
                                                }else if($dataPresensi[$tgl]->bukti_masuk == null){
                                                    $status = 'Hadir Tanpa Absen Masuk';
                                                }else if($pulang < '17:00:00' && $masuk <='08:00:00' ){
                                                    $status='Hadir Pulang Cepat' ; 
                                                }
                                                
                                                @endphp

                                                
                                                @elseif(isset($dataPengajuan[$tgl])) 
                                                @php
                                                 if($dataPengajuan[$tgl]->
                                                        status == 'disetujui'){
                                                        $status = $dataPengajuan[$tgl]->alasan == 'sakit' ? "SDK (Sakit
                                                        Dengan
                                                        Keterangan)" : "IDK (Izin Dengan Keterangan)";
                                                    }else{
                                                        $status = '-';
                                                    }
                                                @endphp
                                                {{-- @else
                                                @php $status = 'A'; @endphp
                                                @endif --}}
                                                @elseif ($carbon::now() > $tgl)
                                                @php
                                                    $status = 'A';
                                                @endphp
                                                @else
                                                @php $status = '-'; @endphp
                                                @endif

                                                        {{ $status }}
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

<!-- pdf button -->
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>

<script>
var dataTable;

$(document).ready(function() {
    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
  "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    var month = new Date().getMonth();
    dataTable = $('#logTable').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'pdfHtml5',
            title: 'Log Presensi',
            filename: 'Data Presensi {{ auth()->user()->name }} Bulan '+monthNames[month],
        }]
    });

    $('.buttons-pdf').addClass('btn btn-outline-info btn-sm');
    $('.buttons-pdf').removeClass('dt-button');
    $('.buttons-pdf span').html('<i class="bi bi-filetype-pdf me-1"></i> Export PDF');
});
</script>
@endpush