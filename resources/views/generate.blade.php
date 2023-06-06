@extends("layout.main")

@section("content")
<h4 class="mt-3"><i class="fa-brands fa-searchengin"></i> Generate Jadwal</h4>
 <div class="card p-1 mt-1 ">

 <div class="card-body">
        <button class="btn btn-secondary float-end" onclick="$('#generate').submit()"><i class="fa-brands fa-searchengin"></i> Generate Jadwal</button>
        <button class="btn btn-dark float-end mx-2"data-bs-toggle="modal" data-bs-target="#tambahumum"><i class="fa-solid fa-plus"></i> Tambah Data</button>
    </div>
    <div class="modal fade" id="tambahumum" tabindex="-1" aria-labelledby="tambahumumLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="tambahumumLabel">Masukan Data</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/generate/store" method="post">
              @csrf
                <div class="mb-3">
                </div>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Mapel</label>
                  <select name ="kode_mapel" class="form-select" aria-label="Default select example">
                    <option selected>Pilih Mapel</option>
                    @foreach($mapel_umum as $mapel)
                      <option value="{{ $mapel->kode_umum }}">Mapel Umum | {{ $mapel->mapel }}</option>
                    @endforeach
                    @foreach($mapel_agama as $mapel)
                      <option value="{{ $mapel->kode_agama }}">Mapel Agama | {{ $mapel->mapel }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Kelas</label>
                  <select name="kelas" class="form-select" aria-label="Default select example">
                    <option selected>Pilih Kelas</option>
                    @foreach($data_kelas as $kelas)
                      <option value="{{ $kelas->kelas }}">{{ $kelas->kelas }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Ruang</label>
                  <select name="ruang" class="form-select" aria-label="Default select example">
                    <option selected>Pilih Ruang</option>
                    @foreach($data_ruangan as $ruang)
                      <option value="{{ $ruang->ruang }}">{{ $ruang->ruang }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Guru</label>
                  <select name="nama_guru" class="form-select" aria-label="Default select example">
                    <option selected>Pilih Nama Guru</option>
                    @foreach($data_guru as $guru)
                      <option value="{{ $guru->nama_guru }}">{{ $guru->nama_guru }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Hari</label>
                  <input type="text" class="form-control" name="hari">
                </div>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Waktu</label>
                  <input type="text" class="form-control" name="waktu" placeholder="07:00-08:00">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
            </div>
          </div>
        </div>
      </div>

      <form action='{{ route('generate.store') }}' method='POST' id="generate" enctype='multipart/form-data'>
      @csrf
      <input type='hidden' class='form-control' name='generate' value="true">
      </form>

<div class= "card p-1 mt-1">
<div class="card-body">
<table class="table table-striped">
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
      <a href="/generate/delete/{{ $g->id }}" class="btn btn-danger">Hapus</a>
      </td>
    </tr>
    <?php $count++; ?>
    @endforeach
  </tbody>
</table>
</div>
</div>

</div>
@endsection