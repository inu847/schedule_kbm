@extends("layout.main")

@section("content")
<h4 class="mt-3"><i class="fa-solid fa-business-time"></i> Waktu Tidak Tersedia</h4>
 <div class="card p-1 mt-1 ">
    <div class="card-body">
    <button class="btn btn-dark float-end" data-bs-toggle="modal" data-bs-target="#tambahwaktu"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
    
    
    <div class="modal fade" id="tambahwaktu" tabindex="-1" aria-labelledby="tambahwaktuLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tambahwaktuLabel">Masukan Data Waktu Tidak Tersedia</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <form action="/data-waktu/store" method="post">
        @csrf
      <div class="mb-3">
    <label for="exampleInputHari" class="form-label">Hari</label>
    <input type="text" class="form-control" name="hari" >
  </div>
  <div class="mb-3">
    <label for="exampleInputWaktu" class="form-label">Waktu Tidak Tersedia</label>
    <input type="text" class="form-control" name="waktu" >
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
@foreach($semua_waktu as $waktu)
<div class="modal fade" id="editwaktu{{ $waktu->id }}" tabindex="-1" aria-labelledby="editwaktu{{ $waktu->id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editwaktu{{ $waktu->id }}Label">Edit Data waktu tidak tersedia</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/data-waktu/update" method="post">
        @csrf
        <input type="hidden" value="{{ $waktu->id }}" name="id">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">hari</label>
    <input type="text" class="form-control" value="{{ $waktu->hari }}" name="hari">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Waktu</label>
    <input type="text" class="form-control" value="{{ $waktu->waktu }}" name="waktu">
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
<div >
<table  class="table tabel-data table-striped table-bordered" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Hari</th>
      <th scope="col">Waktu Tidak Tersedia</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
  @foreach($semua_waktu as $waktu)
        <tr>
            <td>{{ $waktu->id }}</td>
            <td>{{ $waktu->hari }}</td>
            <td>{{ $waktu->waktu }}</td>
            <td>
              <button type="button" class="btn btn-warning"  data-bs-toggle="modal" data-bs-target="#editwaktu{{ $waktu->id }}"><i class="fas fa-pencil-alt"></i></button>
              <a href="/data-waktu/delete/{{ $waktu->id }}" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
        </tr>
    @endforeach
  </tbody>
</table>
</div>
</div>
</div>
@endsection