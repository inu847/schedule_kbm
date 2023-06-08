@extends("layout.main2")

@section('title', 'Data Mapel Umum')

@section("content")
@php
  $durasi = [60, 30];
@endphp

{{-- Modal Tambah --}}
<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/mapel-umum/store" method="post">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Kode Mapel Umum</label>
            <input type="text" class="form-control" name="kode_umum">
          </div>
          <div class="form-group">
            <label>Mapel</label>
            <input type="text" class="form-control" name="mapel">
          </div>
          <div class="form-group">
            <label>Kelas</label>
            <select class="form-control select2" name="kelas" aria-label="Default select example">
              @foreach($data_kelas as $kelas)
                <option value="{{ $kelas->kelas }}">  {{ $kelas->kelas }} </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Durasi</label>
            <select name="durasi" id="" class="form-control select2">
              <option value="" selected disabled>Pilih Opsi</option>
              @foreach ($durasi as $item)
                <option value="{{ $item }}">{{ $item }}</option>
              @endforeach
            </select>
            {{-- <input type="number" class="form-control" name="durasi"> --}}
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
@foreach($semua_umum as $umum)
<div class="modal fade" id="editumum{{ $umum->id }}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/mapel-umum/update" method="post">
        @csrf
        <div class="modal-body">
          <input type="hidden" value="{{ $umum->id }}" name="id">
          <div class="form-group">
            <label>Kode Umum</label>
            <input type="text" class="form-control" value="{{ $umum->kode_umum }}" name="umum">
          </div>
          <div class="form-group">
            <label>Mapel</label>
            <input type="text" class="form-control" value="{{ $umum->mapel }}" name="mapel">
          </div>
          <div class="form-group">
            <label>Kelas</label>
            <select class="form-control select2" name="kelas" aria-label="Default select example">
              @foreach($data_kelas as $kelas)
                <option value="{{ $kelas->kelas }}" {{ ($kelas->kelas == $umum->kelas) ? 'selected' : '' }}>{{ $kelas->kelas }} </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Durasi</label>
            <select name="durasi" id="" class="form-control select2">
              <option value="" selected disabled>Pilih Opsi</option>
              @foreach ($durasi as $item)
                <option value="{{ $item }}" {{ ($umum->durasi == $item) ? 'selected' : '' }} >{{ $item }}</option>
              @endforeach
            </select>
            {{-- <input type="number" class="form-control" value="{{ $umum->durasi }}" name="durasi"> --}}
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<div class='card'>
  <div class='card-header'>
    <h3 class='float-left'>Data Mapel Umum</h3>
    <div class="float-right">
      <button class="btn btn-dark float-end" data-toggle="modal" data-target="#modal-default"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
  </div>
  <div class='card-body'>
    <table class="table tabel-data table-striped table-bordered" id="example1" width="100%" cellspacing="0">
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
                  <button type="button" class="btn btn-warning"  data-toggle="modal" data-target="#editumum{{ $umum->id }}"><i class="fas fa-pencil-alt"></i></button>
                  <button class="btn btn-danger" onclick="deleteData({{ $umum->id }})"><i class="fa-solid fa-trash"></i></button>
                  <form action="/mapel-umum/delete/{{ $umum->id }}" method="post" id="delete{{ $umum->id }}">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                  </form>
              </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

 <script>
  function deleteData(id) {
    var result = confirm("Are you sure you want to delete this data?");
    if (result === true) {
      $('#delete'+id).submit();
    } else {
      console.log("Cancel Delete Data.");
    }
  }
</script>
@endsection