@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
@endpush
@section('title', 'Montern')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Pemagang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.beranda') }}">Home</a></li>
                        <li class="breadcrumb-item active">Data Pemagang</li>
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
                            <h3 class="card-title">Tabel Pemagang</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="d-flex">

                            </div>
                            <a class="btn btn-primary btn-sm mb-3" data-toggle="modal" data-target="#addModal">
                                <i class="bi bi-plus-lg"></i> Tambah Pemagang
                            </a>
                            @if(session('msg'))
                            <div class="alert alert-success" role="alert">
                                {{ session('msg') }}
                            </div>
                            @else
                            @error('msg')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                            @endif
                            <table id="pemagangTable" class="table table-bordered table-striped table-responsive-md">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Nama </th>
                                        <th class="text-center">Email </th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @isset($users)
                                    @foreach($users as $user)
                                    <tr>
                                        <td width="5%">{{ $loop->iteration }}</td>
                                        <td class="text-capitalize">{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td width="20%">
                                            <a class="text-decoration-none text-warning" data-toggle="modal"
                                                data-target="#editModal" onclick="showPemagang({{ $user->id }})">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="{{ route('admin.pendaftar.detail', $user->id) }}" class="text-decoration-none text-primary">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            <a href="{{ route('admin.pemagang.delete', $user->id) }}"
                                                class="text-decoration-none text-danger"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus ini?')">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endisset

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

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Pemagang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.pemagang.add') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label>Pilih Pendidikan</label>
                        <select class="form-control" name="pendidikan">
                            <option value="siswa">Siswa</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="pendaftar@gmail.com"
                            name="email">
                        @error('email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Pemagang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.pemagang.add') }}" method="post">
                <div class="modal-body">
                    @csrf
                    @isset($user)
                    <input type="hidden" value="{{ $user->id }}" name="id">
                    <input type="hidden" value="{{ $user->name }}" name="nama">
                    @endisset
                    <div class="form-group">
                        <label>Pendidikan</label>
                        <select class="form-control" name="pendidikan" disabled>
                            <option value="siswa" id="siswa">Siswa</option>
                            <option value="mahasiswa" id="mahasiswa">Mahasiswa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" placeholder="pendaftar@gmail.com"
                            name="editEmail">
                        @error('editEmail')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')
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
    dataTable = $('#pemagangTable').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'pdfHtml5',
            title: 'Data Pemagang',
            filename: 'Data Pemagang',
            exportOptions: {
                columns: [0, 1, 2]
            }
        }],
        "columnDefs": [{
                "orderable": false,
                "targets": [3]
            },
            {
                "searchable": false,
                "targets": [3]
            },
        ]
    });

    $('.buttons-pdf').addClass('btn btn-outline-info btn-sm');
    $('.buttons-pdf').removeClass('dt-button');
    $('.buttons-pdf span').html('<i class="bi bi-filetype-pdf me-1"></i> Export PDF');

});

function showPemagang(id) {
    fetch("pemagang/show/" + id)
        .then((response) => response.json())
        .then(data => {
            document.getElementById('editEmail').value = data.email;
            if (data.pendaftar.pendidikan == 'mahasiswa') {
                document.getElementById('mahasiswa').selected = true;
            } else if (data.pendaftar.pendidikan == 'siswa') {
                document.getElementById('siswa').selected = true;
            }
        });
}
</script>
@endpush