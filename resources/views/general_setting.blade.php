@extends("layout.main2")

@section('title', 'General Setting')

@section("content")
<div class= "card p-1 mt-1">

  <div class="card-header">
    <h3 class="float-left">General Setting</h3>

    <div class="float-right">
      
    </div>
  </div>

  <div class="card-body">
    <form action="{{ route('general-setting.store') }}" method="post">
      @csrf
        @foreach ($data as $item)
          <div class="form-group">
            <label>{{ ucfirst($item->title) }}</label>
            <input type="text" class="form-control" value="{{ $item->value }}" name="value[]" placeholder="Masukkan {{ ucfirst($item->title) }}">
            <input type="hidden" value="{{ $item->name }}" name="name[]" placeholder="Masukkan {{ ucfirst($item->title) }}">
          </div>
        @endforeach
    </div>

    <div class="col-md-4 m-3">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
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