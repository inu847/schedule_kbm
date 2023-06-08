@extends("layout.main2")

@section('title', 'Data Kelas')

@section("content")

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
      <form action="/data-kelas/store" method="post">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Kelas</label>
            <input type="text" class="form-control" name="kelas">
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Jumlah Siswa</label>
            <input type="number" class="form-control" name="jumlah_siswa">
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
@foreach($semua_kelas as $kelas)
<div class="modal fade" id="editKelas{{ $kelas->id }}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/data-kelas/update" method="post">
        @csrf
        <div class="modal-body">
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
    <h3 class='float-left'>Data Kelas</h3>
    <div class="float-right">
      <button class="btn btn-dark float-end" data-toggle="modal" data-target="#modal-default"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
  </div>
  <div class='card-body'>
    <table class="table tabel-data table-striped table-bordered" id="example1" width="100%" cellspacing="0">
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
                <button type="button" class="btn btn-warning"  data-toggle="modal" data-target="#editKelas{{ $kelas->id }}"><i class="fas fa-pencil-alt"></i></button>
                <button class="btn btn-danger" onclick="deleteData({{ $kelas->id }})"><i class="fa-solid fa-trash"></i></button>
                <form action="/data-kelas/delete/{{ $kelas->id }}" method="post" id="delete{{ $kelas->id }}">
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