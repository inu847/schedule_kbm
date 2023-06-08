@extends("layout.main")

@section("content")
<h4 class="mt-3"><i class="fa-solid fa-house"></i> Data Kelas</h4>
 <div class="card p-1 mt-1 ">
    <div class="card-body">
        <button class="btn btn-dark float-end" data-bs-toggle="modal" data-bs-target="#tambahkelas"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
    <!-- modal tambah -->
    <div class="modal fade" id="tambahkelas" tabindex="-1" aria-labelledby="tambahkelasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tambahkelasLabel">Masukan Data Kelas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
      <form action="/data-kelas/store" method="post">
        @csrf
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Kelas</label>
    <input type="text" class="form-control" name="kelas">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Jumlah Siswa</label>
    <input type="number" class="form-control" name="jumlah_siswa">
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
@foreach($semua_kelas as $kelas)
<div class="modal fade" id="editKelas{{ $kelas->id }}" tabindex="-1" aria-labelledby="editKelas{{ $kelas->id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editKelas{{ $kelas->id }}Label">Edit Data Kelas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/data-kelas/update" method="post">
        @csrf
        <input type="hidden" value="{{ $kelas->id }}" name="id">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Kelas</label>
    <input type="text" class="form-control" value="{{ $kelas->kelas }}" name="kelas">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Jumlah Siswa</label>
    <input type="number" class="form-control" value="{{ $kelas->jumlah_siswa }}" name="jumlah_siswa">
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
<table class="table tabel-data table-striped table-bordered" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Kelas</th>
      <th scope="col">Jumlah Siswa</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
  @foreach($semua_kelas as $kelas)
        <tr>
            <td>{{ $kelas->id }}</td>
            <td>{{ $kelas->kelas }}</td>
            <td>{{ $kelas->jumlah_siswa }}</td>
            <td>
              <button type="button" class="btn btn-warning"  data-bs-toggle="modal" data-bs-target="#editKelas{{ $kelas->id }}"><i class="fas fa-pencil-alt"></i></button>
              <a href="/data-kelas/delete/{{ $kelas->id }}" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
        </tr>
    @endforeach
  </tbody>
</table>
</div>
</div>
 </div>
@endsection