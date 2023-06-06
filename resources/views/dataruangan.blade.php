@extends("layout.main")

@section("content")
<h4 class="mt-3"><i class="fa-sharp fa-light fa-landmark"></i> Data Ruangan</h4>
 <div class="card p-1 mt-1 ">
    <div class="card-body">

    <button class="btn btn-dark float-end"data-bs-toggle="modal" data-bs-target="#tambahruangan"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
    <div class="modal fade" id="tambahruangan" tabindex="-1" aria-labelledby="tambahruanganLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tambahruanganLabel">Masukan Data Ruangan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/data-ruangan/store" method="post">
        @csrf
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Ruang</label>
    <input type="text" class="form-control" name="ruang">
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
</form>


<!-- Modal Edit -->
@foreach($semua_ruang as $ruang)
<div class="modal fade" id="editruang{{ $ruang->id }}" tabindex="-1" aria-labelledby="editruang{{ $ruang->id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editruang{{ $ruang->id }}Label">Edit Data ruang</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/data-ruangan/update" method="post">
        @csrf
        <input type="hidden" value="{{ $ruang->id }}" name="id">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Ruang</label>
    <input type="text" class="form-control" value="{{ $ruang->ruang }}" name="ruang">
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
</form>
@endforeach



<div class= "card p-1 mt-1">
<div>
<table  class="table tabel-data table-striped table-bordered" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Ruang</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
  @foreach($semua_ruang as $ruang)
        <tr>
            <td>{{ $ruang->id }}</td>
            <td>{{ $ruang->ruang }}</td>
            <td><button type="button" class="btn btn-warning"  data-bs-toggle="modal" data-bs-target="#editruang{{ $ruang->id }}">Edit</button> <a href="/data-ruangan/delete/{{ $ruang->id }}" class="btn btn-danger">Hapus</a></td>
        </tr>
    @endforeach
  </tbody>
</table>
</div>
</div>
 </div>
@endsection