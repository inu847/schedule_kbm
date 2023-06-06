@extends("layout.main")

@section("content")
<h4 class="mt-3"><i class="fa-solid fa-chalkboard-user"></i> Data Guru</h4>
 <div class="card p-1 mt-1 ">
    <div class="card-body">

<!-- modal tambah data -->

        <button class="btn btn-dark float-end" data-bs-toggle="modal" data-bs-target="#tambahguru"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
    <div class="modal fade" id="tambahguru" tabindex="-1" aria-labelledby="tambahguruLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tambahguruLabel">Masukan Data Guru</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
      <form action="/data-guru/store" method="post">
        @csrf
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Nama Guru</label>
    <input type="text" class="form-control" name="nama_guru">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Jabatan</label>
    <input type="text" class="form-control" name="jabatan">
  </div>
  <div class="mb-3">
    
    <label for="exampleInputPassword1" class="form-label">Mapel </label>
    <!-- pengambilan data dari mapel  -->
    <select class="form-select" name="mapel" aria-label="Default select example">
    @foreach($mapel_umum as $mapel)
  <option value="{{ $mapel->mapel }}"> Mapel Umum | {{ $mapel->mapel }} </option>
  @endforeach
  @foreach($mapel_agama as $mapel)
  <option value="{{ $mapel->mapel }}">Mapel Agama | {{ $mapel->mapel }} </option>
  @endforeach
</select>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Nomor HP </label>
    <input type="number" class="form-control" name="no_hp">
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
@foreach($semua_guru as $guru)
<div class="modal fade" id="editguru{{ $guru->id }}" tabindex="-1" aria-labelledby="editguru{{ $guru->id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editguru{{ $guru->id }}Label">Edit Data guru</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/data-guru/update" method="post">
        @csrf
        <input type="hidden" value="{{ $guru->id }}" name="id">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Nama guru</label>
    <input type="text" class="form-control" value="{{ $guru->nama_guru }}" name="nama_guru">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">jabatan</label>
    <input type="text" class="form-control" value="{{ $guru->jabatan }}" name="jabatan">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Mapel</label>
    <input type="text" class="form-control" value="{{ $guru->mapel }}" name="mapel">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Nomor HP</label>
    <input type="number" class="form-control" value="{{ $guru->no_hp }}" name="no_hp">
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



<div class= "p-1 mt-1">
<div>

<table class="table tabel-data table-striped table-bordered" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Nama Guru</th>
      <th scope="col">Jabatan</th>
      <th scope="col">Mapel</th>
      <th scope="col">Nomor HP</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach($semua_guru as $guru)
        <tr>
            <td>{{ $guru->id }}</td>
            <td>{{ $guru->nama_guru }}</td>
            <td>{{ $guru->jabatan }}</td>
            <td>{{ $guru->mapel }}</td>
            <td>{{ $guru->no_hp }}</td>
            <td><button type="button" class="btn btn-warning"  data-bs-toggle="modal" data-bs-target="#editguru{{ $guru->id }}">Edit</button> <a href="/data-guru/delete/{{ $guru->id }}" class="btn btn-danger">Hapus</a></td>
        </tr>
    @endforeach
  </tbody>
</table>

</div>
</div>
 </div>
@endsection