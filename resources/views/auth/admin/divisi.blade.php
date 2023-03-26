@extends('layouts.app')

@section('title', 'Montern')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Divisi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.beranda') }}">Home</a></li>
                        <li class="breadcrumb-item active">Data Divisi</li>
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
                            <h3 class="card-title">Tabel Divisi</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="d-flex">

                            </div>
                            <a class="btn btn-primary btn-sm mb-3" data-toggle="modal" data-target="#addModal">
                                <i class="bi bi-plus-lg"></i> Tambah Divisi
                            </a>
                            <table id="durasiTabel" class="table table-bordered table-striped table-responsive-md">
                                <thead class="text-center">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Divisi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @isset($divisi)
                                    @foreach($divisi as $dvs)
                                    <tr>
                                        <td width="5%">{{ $loop->iteration }}</td>
                                        <td class="text-capitalize">{{ $dvs->nama_divisi }}</td>
                                        <td width="10%">
                                            <a href="javascript:void(0)" class="text-decoration-none text-warning"
                                                data-toggle="modal" data-target="#editModal"
                                                onclick="showDivisi({{ $dvs->id }})">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="{{ route('admin.divisi.delete', $dvs->id) }}"
                                                class="text-decoration-none text-danger"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus ini?')"><i
                                                    class="bi bi-trash3"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endisset

                                    @if($divisi->isEmpty())
                                    <td colspan="5">Data tidak ada.</td>
                                    @endif
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
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Divisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.divisi.add') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="divisi" class="form-label">Nama Divisi</label>
                        <input type="text" class="form-control" id="divisi" placeholder="Sumber Daya Manusia (SDM)"
                            name="divisi">
                        @error('divisi')
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
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Divisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.divisi.add') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="editIdDivisi" value="" id="editIdDivisi">
                    <div class="mb-3">
                        <label for="editDivisi" class="form-label">Nama Divisi</label>
                        <input type="text" class="form-control" id="editDivisi" placeholder="Sumber Daya Manusia (SDM)"
                            name="editDivisi">
                        @error('editDivisi')
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
<script>
function showDivisi(id) {
    fetch("divisi/show/" + id)
        .then((response) => response.json())
        .then(data => {
            document.getElementById('editIdDivisi').value = data.id;
            document.getElementById('editDivisi').value = data.nama_divisi;
        });
}
</script>
@endpush