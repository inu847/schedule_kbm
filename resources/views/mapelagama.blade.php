@extends("layout.main2")

@section("title", "Data Mapel Agama")

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
      <form action="/mapel-agama/store" method="post">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Kode Mapel Agama</label>
            <input type="text" class="form-control" name="kode_agama">
          </div>
          <div class="form-group">
            <label>Mapel</label>
            <input type="text" class="form-control" name="mapel">
          </div>
          {{-- <div class="form-group">
            <label>Kelas</label>
            <select class="form-control select2" name="kelas" aria-label="Default select example">
              @foreach($data_kelas as $kelas)
                <option value="{{ $kelas->kelas }}">  {{ $kelas->kelas }} </option>
                @endforeach
            </select>
          </div> --}}
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

<!-- Modal Edit -->
@foreach($semua_agama as $agama)
<div class="modal fade" id="editAgama{{ $agama->id }}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/mapel-agama/update" method="post">
        @csrf
        <div class="modal-body">
          <input type="hidden" value="{{ $agama->id }}" name="id">
          <div class="form-group">
            <label>Kode agama</label>
            <input type="text" class="form-control" value="{{ $agama->kode_agama }}" name="agama">
          </div>
          <div class="form-group">
            <label>Mapel</label>
            <input type="text" class="form-control" value="{{ $agama->mapel }}" name="mapel">
          </div>
          {{-- <div class="form-group">
            <label>Kelas</label>
            <select class="form-control select2" name="kelas" aria-label="Default select example">
              @foreach($data_kelas as $kelas)
                <option value="{{ $kelas->kelas }}" {{ ($kelas->kelas == $agama->kelas) ? 'selected' : '' }}>{{ $kelas->kelas }} </option>
                @endforeach
            </select>
          </div> --}}
          <div class="form-group">
            <label>Durasi</label>
            <select name="durasi" id="" class="form-control select2">
              <option value="" selected disabled>Pilih Opsi</option>
              @foreach ($durasi as $item)
                <option value="{{ $item }}" {{ ($agama->durasi == $item) ? 'selected' : '' }} >{{ $item }}</option>
              @endforeach
            </select>
            {{-- <input type="number" class="form-control" value="{{ $agama->durasi }}" name="durasi"> --}}
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
    <h3 class="float-left">Data Mapel Agama</h3>
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
          {{-- <th scope="col">Kelas</th> --}}
          <th scope="col">Durasi (Menit)</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @foreach($semua_agama as $key => $agama)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $agama->kode_agama }}</td>
                <td>{{ $agama->mapel }}</td>
                {{-- <td>{{ $agama->kelas }}</td> --}}
                <td>{{ $agama->durasi }}</td>
                <td><button type="button" class="btn btn-warning"  data-toggle="modal" data-target="#editAgama{{ $agama->id }}"><i class="fas fa-pencil-alt"></i></button>
                  <button class="btn btn-danger" onclick="deleteData({{ $agama->id }})"><i class="fa-solid fa-trash"></i></button>
                  <form action="/mapel-agama/delete/{{ $agama->id }}" method="post" id="delete{{ $agama->id }}">
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