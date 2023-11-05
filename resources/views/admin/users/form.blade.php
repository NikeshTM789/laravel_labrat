@push('styles')
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush
<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
  @csrf
  {{ $slot }}
  <div class="card-body">
    <div class="row">
      <div class="col-8">
        <x-adminlte-select2 name="role" label="Role" data-placeholder="Select an role..." :config="['allowClear' => true]">
            <option/>
            @foreach ($roles as $role)
            <option value="{{ $role->id }}" @selected($user->roles()->where('id', $role->id)->exists())>{{ $role->name }}</option>
            @endforeach
            @error('role')
              <div class="text-danger">{{ $message }}</div>
            @enderror
        </x-adminlte-select2>
  {{--       <div class="form-group">
          <label>Role</label>
          <select name="role" class="form-control select2" style="width: 100%;">
            @foreach ($roles as $role)
            <option></option>
            <option value="{{ $role->id }}" @selected($user->roles()->where('id', $role->id)->exists())>{{ $role->name }}</option>
            @endforeach
          </select>
          @error('role')
          <div class="text-danger">{{ $message }}</div>
          @enderror
        </div> --}}
      <div class="form-group">
        <label for="fullName">Full Name</label>
        <input type="name" name="name" value="{{ $user->name ?? old('name','') }}" class="form-control" id="fullName" placeholder="Full Name">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email"name="email" value="{{ $user->email ?? old('email','') }}" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      </div>
      <div id="previewContainer" data-default="{{ $user->getFirstMediaUrl('user') }}" class="align-items-center col-4 d-flex flex-column">
      </div>
    </div>
    <div class="form-group">
      <label for="fileInput">File input</label>
      <div class="input-group">
        <div class="custom-file">
          <input type="file" name="profile" class="custom-file-input" id="fileInput">
          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
        </div>
        <div class="input-group-append">
          <span class="input-group-text">Upload</span>
        </div>
      </div>
    </div>
  </div>
  <!-- /.card-body -->
  <div class="card-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
    @if ($user->id)
    <a href="#" class="btn btn-link btn-danger text-light float-right dt-delete" data-url="{{ route('admin.user.delete.media', $user->id) }}">Remove current image</a>
    @endif
  </div>
</form>


@section('js')
@yield('datatable')
<script type="text/javascript">
const custom_select = "{{ old('role','') }}";
select2('Select a role', false, custom_select);


var previewContainer = document.getElementById('previewContainer');
//restore default image
function createImagePlaceholder() {
    let imageDefault = document.createElement('img');
    imageDefault.className = 'align-self-center w-75';
    imageDefault.id = 'default-profile-img';
    imageDefault.src = previewContainer.dataset.default; // default image source here
    previewContainer.appendChild(imageDefault);
}
function resetFileInput(inputFileEl){
  createImagePlaceholder();
  inputFileEl.nextElementSibling.innerHTML = 'Choose file';
  inputFileEl.value = '';
}
function handleFileSelect(event) {
    if (previewContainer.hasChildNodes()) {
        while (previewContainer.firstChild) {
            previewContainer.removeChild(previewContainer.firstChild);
        }
    }
    var file = event.target.files[0]; // Get the selected files
    if (file.type.match('image.*')) {
        var reader = new FileReader();
        // Closure to capture the file information
        reader.onload = (function(theFile) {
            return function(e) {
                // Create a preview image
                var image = document.createElement('img');
                image.className = 'uploaded-img align-self-center w-75';
                image.src = e.target.result;
                // Create a delete button
                var deleteButton = document.createElement('button');
                deleteButton.innerText = 'Delete';
                deleteButton.type = 'button';
                deleteButton.className = 'align-self-center btn btn-outline-danger btn-sm w-75';
                // Remove the image and the button from the preview container
                deleteButton.addEventListener('click', function(e) {
                    let parentEl = e.target.parentElement;
                    image.parentNode.removeChild(image);
                    deleteButton.parentNode.removeChild(deleteButton);
                    if (parentEl.querySelectorAll('.uploaded-img').length == 0) {
                      resetFileInput(event.target);
                    }
                });
                // Append the image and the button to the preview container
                previewContainer.appendChild(image);
                previewContainer.appendChild(deleteButton);
            };
        })(file);
        // Read the image file as a data URL
        reader.readAsDataURL(file);
    } else {
        alert('please upload image file');
        setTimeout(function() {
          resetFileInput(event.target);
        }, 0);
    }
}
// Listen for file selection
document.getElementById('fileInput').addEventListener('change', handleFileSelect, false);
createImagePlaceholder();
deleteEventListener();
</script>
@endsection