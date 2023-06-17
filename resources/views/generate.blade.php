@extends("layout.main2")

@section('title', 'Generate Jadwal')

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

  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="/generate/store" method="post">
            @csrf
              <div class="form-group">
                <label>Mapel</label>
                <select name="kode_mapel" style="padding: 10px 0px;" class="form-control select2" style="width: 100%;">
                  <option selected>Pilih Mapel</option>
                  @foreach($mapel_umum as $mapel)
                    <option value="{{ $mapel->kode_umum }}">Mapel Umum | {{ $mapel->mapel }}</option>
                  @endforeach
                  @foreach($mapel_agama as $mapel)
                    <option value="{{ $mapel->kode_agama }}">Mapel Agama | {{ $mapel->mapel }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Kelas</label>
                <select name="kelas" class="form-control select2">
                  <option selected>Pilih Kelas</option>
                  @foreach($data_kelas as $kelas)
                    <option value="{{ $kelas->kelas }}">{{ $kelas->kelas }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Ruang</label>
                <select name="ruang" class="form-control select2" aria-label="Default select example">
                  <option selected>Pilih Ruang</option>
                  @foreach($data_ruangan as $ruang)
                    <option value="{{ $ruang->ruang }}">{{ $ruang->ruang }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Guru</label>
                <select name="nama_guru" class="form-control select2" aria-label="Default select example">
                  <option selected>Pilih Nama Guru</option>
                  @foreach($data_guru as $guru)
                    <option value="{{ $guru->nama_guru }}">{{ $guru->nama_guru }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Hari</label>
                <select name="ruang" class="form-control select2" aria-label="Default select example">
                  <option selected>Pilih Hari</option>
                  @foreach($dayOfWeek as $day)
                    <option value="{{ $day }}">{{ $day }}</option>
                  @endforeach
                </select>
                {{-- <input type="text" class="form-control" name="hari"> --}}
              </div>
              <div class="form-group">
                <label>Waktu</label>
                <input type="text" class="form-control" name="waktu" placeholder="07:00-08:00">
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

  <form action='{{ route('generate.store') }}' method='POST' id="generate" enctype='multipart/form-data'>
    @csrf
    <input type='hidden' class='form-control' name='generate' value="true">
  </form>

  <form action='{{ route('generate.deleteAll') }}' method='POST' id="delete-all" enctype='multipart/form-data'>
    @csrf
  </form>

<div class= "card p-1 mt-1">

  <div class="card-header">
    <h3 class="float-left">Jadwal Pelajaran</h3>

    <div class="float-right">
      <button class="btn btn-danger float-end" onclick="$('#delete-all').submit()"><i class="fas fa-trash"></i> Hapus Semua Jadwal</button>
      <button class="btn btn-secondary float-end" onclick="$('#generate').submit()"><i class="fa-brands fa-searchengin"></i> Generate Jadwal</button>
      {{-- <button class="btn btn-dark float-end" data-toggle="modal" data-target="#modal-default"><i class="fa-solid fa-plus"></i> Tambah Data</button> --}}
    </div>
  </div>

  <div class="card-body">
  <table class="table table-striped" id="example1">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Kode Mapel</th>
        <th scope="col">Mapel</th>
        <th scope="col">Kelas</th>
        <th scope="col">Ruang</th>
        <th scope="col">Nama Guru</th>
        <th scope="col">Hari</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $count = 1; ?>
      @foreach($generate as $g)
      <tr>
        <td>{{ $count }}</td>
        <td>{{ $g->kode_mapel }}</td>
        <td>{{ $g->mapel }}</td>
        <td>{{ $g->kelas }}</td>
        <td>{{ $g->ruang }}</td>
        <td>{{ $g->nama_guru }}</td>
        <td>{{ $g->hari.", ".$g->waktu }}</td>
        <td>
          <button class="btn btn-danger" onclick="deleteData({{ $g->id }})"><i class="fa-solid fa-trash"></i></button>
          <form action="/generate/delete/{{ $g->id }}" method="post" id="delete{{ $g->id }}">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
          </form>
        </td>
      </tr>
      <?php $count++; ?>
      @endforeach
    </tbody>
  </table>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#mySelect').select2();
  });

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