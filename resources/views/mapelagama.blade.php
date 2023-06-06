@extends("layout.main")

@section("content")
<h4 class="mt-3"><i class="fa-solid fa-book-open-reader"></i> Data Mapel Agama</h4>
 <div class="card p-1 mt-1 ">
    <div class="card-body">

    <!-- modal tambah data -->
    <button class="btn btn-dark float-end"data-bs-toggle="modal" data-bs-target="#tambahagama"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
    <div class="modal fade" id="tambahagama" tabindex="-1" aria-labelledby="tambahagamaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tambahagamaLabel">Masukan Data Mapel Agama</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <form action="/mapel_agama/store" method="post">
        @csrf
      <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Kode Mapel Agama</label>
    <input type="text" class="form-control" name="kode_agama">
  </div>
      <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Mapel</label>
    <input type="text" class="form-control" name="mapel">
  </div>
  <div class="mb-3">
<!-- pengambilan data -->
    <label for="exampleInputEmail1" class="form-label">Kelas</label>
    <select class="form-select" name="kelas" aria-label="Default select example">
    @foreach($data_kelas as $kelas)
  <option value="{{ $kelas->kelas }}">  {{ $kelas->kelas }} </option>
  @endforeach
</select>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Durasi</label>
    <input type="number" class="form-control" name="durasi">
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
@foreach($semua_agama as $agama)
<div class="modal fade" id="editAgama{{ $agama->id }}" tabindex="-1" aria-labelledby="editAgama{{ $agama->id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editagama{{ $agama->id }}Label">Edit Data agama</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/mapel-agama/update" method="post">
        @csrf
        <input type="hidden" value="{{ $agama->id }}" name="id">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Kode agama</label>
    <input type="text" class="form-control" value="{{ $agama->kode_agama }}" name="agama">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Mapel</label>
    <input type="text" class="form-control" value="{{ $agama->mapel }}" name="mapel">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Kelas</label>
    <input type="text" class="form-control" value="{{ $agama->kelas }}" name="kelas">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Durasi</label>
    <input type="number" class="form-control" value="{{ $agama->durasi }}" name="durasi">
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
      <th scope="col">Kode Mapel</th>
      <th scope="col">Mapel</th>
      <th scope="col">Kelas</th>
      <th scope="col">Durasi (Menit)</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
  @foreach($semua_agama as $agama)
        <tr>
            <td>{{ $agama->id }}</td>
            <td>{{ $agama->kode_agama }}</td>
            <td>{{ $agama->mapel }}</td>
            <td>{{ $agama->kelas }}</td>
            <td>{{ $agama->durasi }}</td>
            <td><button type="button" class="btn btn-warning"  data-bs-toggle="modal" data-bs-target="#editAgama{{ $agama->id }}">Edit</button> <a href="/data-agama/delete/{{ $agama->id }}" class="btn btn-danger">Hapus</a></td>
        </tr>
    @endforeach
  </tbody>
</table>
</div>
</div>
 </div>
@endsection