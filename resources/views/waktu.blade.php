@extends("layout.main2")

@section('title', 'Data Waktu Tidak Tersedia')

@section("content")
@php
  $dayOfWeek = [
      'Senin',
      'Selasa',
      'Rabu',
      'Kamis',
      'Jumat',
      'Sabtu'
  ];
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
      <form action="/data-waktu/store" method="post">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Hari</label>
            <select name="hari" class="form-control select2">
              <option value="" selected disabled>Pilih Opsi</option>
              @foreach($dayOfWeek as $day)
                <option value="{{ $day }}">{{ $day }}</option>
              @endforeach
            </select>
          </div>
          {{-- <div class="form-group">
            <label>Waktu Tidak Tersedia</label>
            <input type="text" class="form-control" name="waktu" >
          </div> --}}
          <div class="form-group">
            <label>Waktu:</label>
            <input type="text" class="form-control" name="waktu" data-inputmask-alias="datetime" data-inputmask-inputformat="HH:MM - HH:MM" data-mask>
            <!-- /.input group -->
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

<!-- Modal Edit -->
@foreach($semua_waktu as $waktu)
<div class="modal fade" id="editwaktu{{ $waktu->id }}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/data-waktu/update" method="post">
        @csrf
        <div class="modal-body">
          <input type="hidden" value="{{ $waktu->id }}" name="id">
          <div class="form-group">
            <label>Hari</label>
            <select name="hari" class="form-control select2">
              <option value="" selected disabled>Pilih Opsi</option>
              @foreach($dayOfWeek as $day)
                <option value="{{ $day }}" {{ ($day == $waktu->hari) ? 'selected' : '' }}>{{ $day }}</option>
              @endforeach
            </select>
          </div>
          {{-- <div class="form-group">
            <label>Waktu</label>
            <input type="text" class="form-control" value="{{ $waktu->waktu }}" name="waktu">
          </div> --}}
          <div class="form-group">
            <label>Waktu:</label>
            <input type="text" class="form-control" value="{{ $waktu->waktu }}" name="waktu" data-inputmask-alias="datetime" data-inputmask-inputformat="HH:MM - HH:MM" data-mask>
            <!-- /.input group -->
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
    <h3 class='float-left'>Data Waktu Tidak Tersedia</h3>
    <div class="float-right">
      <button class="btn btn-dark float-end" data-toggle="modal" data-target="#modal-default"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
  </div>
  <div class='card-body'>
    <table  class="table tabel-data table-striped table-bordered" id="example1" width="100%" cellspacing="0">
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
              <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editwaktu{{ $waktu->id }}"><i class="fas fa-pencil-alt"></i></button>
              <button class="btn btn-danger" onclick="deleteData({{ $waktu->id }})"><i class="fa-solid fa-trash"></i></button>
              <form action="/data-waktu/delete/{{ $waktu->id }}" method="post" id="delete{{ $waktu->id }}">
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