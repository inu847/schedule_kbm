@extends("layout.main2")

@section('title', 'Data Guru')

@section("content")

{{-- Tambah Data --}}
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
        <form action="/data-guru/store" method="post">
          @csrf
            <div class="form-group">
              <label>Nama Guru</label>
              <small class="text-danger" id="message_input_guru" hidden>Hanya Huruf Alfabet</small>
              <input type="text" class="form-control" id="nama_guru" name="nama_guru">
            </div>
            <div class="form-group">
              <label>Jabatan</label>
              <input type="text" class="form-control" name="jabatan">
            </div>
            <div class="form-group">
              <label>Mapel </label>
              <select name="code_mapel" id="" class="form-control">
                <option value="" selected disabled>Pilih Opsi</option>
                @foreach ($mapel as $item)
                    <option value="{{ $item->code_mapel }}">{{ $item->code_mapel." - ".$item->mapel }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Nomor HP </label>
              <input type="number" class="form-control" name="no_hp">
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="save">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
@foreach ($semua_guru as $guru)
  <div class="modal fade" id="editguru{{ $guru->id }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="/data-guru/update" method="post">
            @csrf
            <input type="hidden" value="{{ $guru->id }}" name="id">
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Nama guru</label>
              <small class="text-danger" id="message_input_guru_edit" hidden>Hanya Huruf Alfabet</small>
              <input type="text" class="form-control" id="nama_guru_edit" value="{{ $guru->nama_guru }}" name="nama_guru">
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">jabatan</label>
              <input type="text" class="form-control" value="{{ $guru->jabatan }}" name="jabatan">
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Mapel</label>
              <select name="code_mapel" id="" class="form-control">
                <option value="" selected disabled>Pilih Opsi</option>
                @foreach ($mapel as $item)
                    <option value="{{ $item->code_mapel }}" {{ ($item->code_mapel == $guru->code_mapel) ? 'selected' : '' }}>{{ $item->code_mapel." - ".$item->mapel }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Nomor HP</label>
              <input type="number" class="form-control" value="{{ $guru->no_hp }}" name="no_hp">
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="save_edit">Submit</button>
        </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

<div class='card'>
  <div class='card-header'>
    <h3 class="float-left">Data Guru</h3>
    <div class="float-right">
      <button class="btn btn-dark float-end" data-toggle="modal" data-target="#modal-default"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
  </div>
  <div class='card-body'>
    <table class="table tabel-data table-striped table-bordered" id="example1" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama Guru</th>
          <th scope="col">Jabatan</th>
          <th scope="col">Mapel</th>
          <th scope="col">Nomor HP</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($semua_guru as $key => $guru)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $guru->nama_guru }}</td>
                <td>{{ $guru->jabatan }}</td>
                <td>{{ $guru->mapel }}</td>
                <td>{{ $guru->no_hp }}</td>
                <td>
                  <button type="button" class="btn btn-warning"  data-toggle="modal" data-target="#editguru{{ $guru->id }}"><i class="fas fa-pencil-alt"></i></button>
                  <button class="btn btn-danger" onclick="deleteData({{ $guru->id }})"><i class="fa-solid fa-trash"></i></button>
                  <form action="/data-guru/delete/{{ $guru->id }}" method="post" id="delete{{ $guru->id }}">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                  </form>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<script src="{{ asset('template2/plugins/jquery/jquery.min.js')}}"></script>
<script>
  $(document).ready(function() {
    // var inputField = $("#nama_guru");
    // inputField.addEventListener("input", validateInput);
    $('#nama_guru').on('input', function() {
      validateInput();
    });

    // var inputField = $("#nama_guru_edit");
    // inputField.addEventListener("input", validateInputEdit);
    $('#nama_guru_edit').on('input', function() {
      validateInputEdit();
    });
  });
  
  function validateInput() {
    var inputValue = $('#nama_guru').val().trim();
    
    var regex = /^[a-zA-Z\s]*$/;
    
    if (regex.test(inputValue)) {
      console.log("Valid input: " + inputValue);
      document.getElementById("message_input_guru").setAttribute("hidden", "hidden");
      document.getElementById("save").removeAttribute("disabled");
    } else {
      console.log("Invalid input: " + inputValue);
      document.getElementById("message_input_guru").removeAttribute("hidden");
      document.getElementById("save").setAttribute("disabled", "disabled");
    }
  }

  function validateInputEdit() {
    var inputValue = $('#nama_guru_edit').val().trim();
    
    var regex = /^[a-zA-Z\s]*$/;
    
    if (regex.test(inputValue)) {
      console.log("Valid input: " + inputValue);
      document.getElementById("message_input_guru_edit").setAttribute("hidden", "hidden");
      document.getElementById("save_edit").removeAttribute("disabled");
    } else {
      console.log("Invalid input: " + inputValue);
      document.getElementById("message_input_guru_edit").removeAttribute("hidden");
      document.getElementById("save_edit").setAttribute("disabled", "disabled");
    }
  }
</script>
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