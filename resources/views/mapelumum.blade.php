@extends("layout.main")

@section("content")
@php
  $durasi = [60, 30];
@endphp

<h4 class="mt-3"><i class="fa-solid fa-book-open-reader"></i> Data Mapel Umum</h4>
 <div class="card p-1 mt-1 ">
    <div class="card-body">

<!-- modal tambah data -->
        <button class="btn btn-dark float-end"data-bs-toggle="modal" data-bs-target="#tambahumum"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
    <div class="modal fade" id="tambahumum" tabindex="-1" aria-labelledby="tambahumumLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tambahumumLabel">Masukan Data Mapel Umum</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <form action="/mapel-umum/store" method="post">
        @csrf
      <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Kode Mapel Umum</label>
    <input type="text" class="form-control" name="kode_umum">
  </div>
      <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Mapel</label>
    <input type="text" class="form-control" name="mapel">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Kelas</label>
    <select class="form-select" name="kelas" aria-label="Default select example">
    @foreach($data_kelas as $kelas)
  <option value="{{ $kelas->kelas }}">  {{ $kelas->kelas }} </option>
  @endforeach
</select>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Durasi</label>
    <select name="durasi" id="" class="form-control">
      <option value="" selected disabled>Pilih Opsi</option>
      @foreach ($durasi as $item)
        <option value="{{ $item }}">{{ $item }}</option>
      @endforeach
    </select>
    {{-- <input type="number" class="form-control" name="durasi"> --}}
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
@foreach($semua_umum as $umum)
<div class="modal fade" id="editumum{{ $umum->id }}" tabindex="-1" aria-labelledby="editumum{{ $umum->id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editumum{{ $umum->id }}Label">Edit Data umum</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/mapel-umum/update" method="post">
        @csrf
        <input type="hidden" value="{{ $umum->id }}" name="id">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Kode Umum</label>
    <input type="text" class="form-control" value="{{ $umum->kode_umum }}" name="umum">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Mapel</label>
    <input type="text" class="form-control" value="{{ $umum->mapel }}" name="mapel">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Kelas</label>
    <input type="text" class="form-control" value="{{ $umum->kelas }}" name="kelas">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Durasi</label>
    <select name="durasi" id="" class="form-control">
      <option value="" selected disabled>Pilih Opsi</option>
      @foreach ($durasi as $item)
        <option value="{{ $item }}" {{ ($agama->durasi == $item) ? 'selected' : '' }} >{{ $item }}</option>
      @endforeach
    </select>
    {{-- <input type="number" class="form-control" value="{{ $umum->durasi }}" name="durasi"> --}}
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
  @foreach($semua_umum as $umum)
        <tr>
            <td>{{ $umum->id }}</td>
            <td>{{ $umum->kode_umum }}</td>
            <td>{{ $umum->mapel }}</td>
            <td>{{ $umum->kelas }}</td>
            <td>{{ $umum->durasi }}</td>
            <td>
              <button type="button" class="btn btn-warning"  data-bs-toggle="modal" data-bs-target="#editumum{{ $umum->id }}"><i class="fas fa-pencil-alt"></i></button>
              <a href="/data-umum/delete/{{ $umum->id }}" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
        
          </tr>
    @endforeach
  </tbody>
</table>
</div>
</div>
 </div>
@endsection