@extends("layout.main2")

@section('title', 'Data Ruangan')

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
      <form action="/data-ruangan/store" method="post">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Ruang</label>
            <input type="text" class="form-control" name="ruang">
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
@foreach($semua_ruang as $ruang)
<div class="modal fade" id="editruang{{ $ruang->id }}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/data-ruangan/update" method="post">
        @csrf
        <div class="modal-body">
          <input type="hidden" value="{{ $ruang->id }}" name="id">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Ruang</label>
            <input type="text" class="form-control" value="{{ $ruang->ruang }}" name="ruang">
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
    <h3 class='float-left'>Data Ruangan</h3>
    <div class="float-right">
      <button class="btn btn-dark float-end" data-toggle="modal" data-target="#modal-default"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
  </div>
  <div class='card-body'>
    <table  class="table tabel-data table-striped table-bordered" id="example1" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Ruang</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($semua_ruang as $ruang)
          <tr>
            <td>{{ $ruang->id }}</td>
            <td>{{ $ruang->ruang }}</td>
            <td><button type="button" class="btn btn-warning"  data-toggle="modal" data-target="#editruang{{ $ruang->id }}"><i class="fas fa-pencil-alt"></i></button>
            <button class="btn btn-danger" onclick="deleteData({{ $ruang->id }})"><i class="fa-solid fa-trash"></i></button>
              <form action="/data-ruangan/delete/{{ $ruang->id }}" method="post" id="delete{{ $ruang->id }}">
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