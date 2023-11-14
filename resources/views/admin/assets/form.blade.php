@push('css')
  <link rel="stylesheet" href="{{ asset('packages/bs-stepper/css/bs-stepper.min.css') }}">
  <link rel="stylesheet" href="{{ asset('packages/dropzone/dropzone.css') }}" type="text/css" />
@endpush

@section('content')
  <div class="bs-stepper">
    <div class="bs-stepper-header" role="tablist">
      <!-- your steps here -->
      <div class="step" data-target="#detail-part">
        <button type="button" class="step-trigger" role="tab" aria-controls="logins-part" id="logins-part-trigger">
          <span class="bs-stepper-circle">1</span>
          <span class="bs-stepper-label">Information</span>
        </button>
      </div>
      <div class="line"></div>
      <div class="step" data-target="#gallery-part">
        <button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
          <span class="bs-stepper-circle">2</span>
          <span class="bs-stepper-label">Gallery</span>
        </button>
      </div>
    </div>
    <div class="bs-stepper-content">
      <!-- your steps content here -->
      <div id="detail-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
        <form method="POST" action="{{ $action }}">
          @csrf
          @isset($update)
            @method('PATCH')
          @endisset
          <x-adminlte-select2 name="categories[]" class="select2" igroup-size="sm" label="Categories" data-placeholder="Select an Category..." :config="['allowClear' => true]" style="width: 100%;" multiple>
              @foreach ($categories as $category)
              <option value="{{ $category->id }}" @selected(optional($product)->categories()->where('id', $category->id)->exists() ?? false)>{{ $category->name }}</option>
              @endforeach
              @error('categories')
                <div class="text-danger">{{ $message }}</div>
              @enderror
          </x-adminlte-select2>

          <div class="form-group">
            <label for="product-name">Product Name</label>
            <input type="text" name="name" value="{{ optional($product)->name ?? old('name') }}" class="form-control form-control-sm" id="product-name" placeholder="Enter Product Name">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="row">
            <div class="form-group col-4">
              <label for="price">Price</label>
              <input type="text" name="price" value="{{ optional($product)->price ?? old('price') }}" class="form-control form-control-sm" id="price" placeholder="Enter Product Name">
              @error('price')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group col-4">
              <label for="discount_percent">Discount Percent</label>
              <input type="text" class="form-control form-control-sm" id="discount_percent" placeholder="Enter Product Name">
            </div>
            <div class="form-group col-4">
              <label for="discounted_price">Discounted Price</label>
              <input type="text" name="discounted_price" value="{{ optional($product)->discounted_price ?? old('discounted_price') }}" class="form-control form-control-sm" id="discounted_price" placeholder="Enter Product Name">
              @error('discounted_price')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="form-group col-6">
              <label for="quantity">Quantity</label>
              <input type="text" name="quantity" value="{{ optional($product)->quantity ?? old('quantity') }}" class="form-control form-control-sm" id="quantity" placeholder="Enter Product Quantity">
              @error('quantity')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>

          </div>
          <textarea name="details" id="summernote">{{ optional($product)->details ?? old('details') }}</textarea>
          @isset($update)
          <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
          @endisset
          <button type="submit" class="btn btn-outline-info">Submit</button>
        </form>
      </div>
      <div id="gallery-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
        @isset($update)
          <form action="{{ route('admin.product.image') }}" class="mb-3 dropzone" id="my-dropzone-gallery">
            @csrf
            <input type="hidden" name="product_uuid" value="{{ $product->uuid }}" />
            <input type="hidden" name="gallery" value="true"/>
          </form>
          <form action="{{ route('admin.product.image') }}" class="mb-3 dropzone" id="my-dropzone-featured">
            @csrf
            <input type="hidden" name="featured" value="true"/>
            <input type="hidden" name="product_uuid" value="{{ $product->uuid }}" />
          </form>
          <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
        @endisset
      </div>
    </div>
  </div>
@stop

@push('js')
<script src="{{ asset('packages/bs-stepper/js/bs-stepper.min.js') }}"></script>
<script src="{{ asset('packages/dropzone/dropzone.js') }}"></script>
<script>
  select2();
  summernote();
  stepper = new Stepper(document.querySelector('.bs-stepper'))

  Dropzone.options.myDropzoneGallery = {
    dictDefaultMessage: 'Drop files here or click to upload gallery image',
    maxFilesize: 1, // Set the maximum file size (in MB)
    maxFiles: 5,
    acceptedFiles: "image/*", // Specify the file types allowed
    addRemoveLinks: true, // Adds links to remove files from the preview area
    init: function () {
      var medias = @json($gallery);
      // You can add custom initialization logic here
      const dz = this;
        medias.forEach(function (media) {
        // Create a mock file object
        var mockFile = { name: media.name, size: media.size, accepted: true };

        // Add the file to the Dropzone preview
        dz.emit("addedfile", mockFile);
        dz.emit("thumbnail", mockFile, media.url);
        dz.emit("complete", mockFile);

        mockFile.previewElement.classList.add("dz-success");
        mockFile.previewElement.classList.add("dz-complete");

        mockFile.id = media.id;
        dz.files.push(mockFile);
      });
    },
    removedfile: function (file) {
      console.log(file);
      var deleteEndpoint = '{{ route('admin.product.image') }}';
      var params = { 
        product_uuid: '{{ $product->uuid }}',
        image_id: file.id 
      };
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": $('input[name="_token"]').val()
        }
      });
      $.post(deleteEndpoint, params, function (data) {
        if(data == 'success'){
          file.previewElement.remove();
        }
      });
    },
    success: function (file, response) {
      // Handle successful uploads here
      file.id = response.image_id;
      // console.log(response.image_id);
    },
    error: function (file, errorMessage) {
      // Handle upload errors here
      console.error(file);
      alert(errorMessage);
    }
  };  

  Dropzone.options.myDropzoneFeatured = {
    dictDefaultMessage: 'Drop files here or click to upload featured image',
    maxFilesize: 1, // Set the maximum file size (in MB)
    maxFiles: 1,
    acceptedFiles: "image/*", // Specify the file types allowed
    addRemoveLinks: true, // Adds links to remove files from the preview area
    init: function () {
      var medias = @json($featured);
      // You can add custom initialization logic here
      const dz = this;
        medias.forEach(function (media) {
        // Create a mock file object
        var mockFile = { name: media.name, size: media.size, accepted: true };

        // Add the file to the Dropzone preview
        dz.emit("addedfile", mockFile);
        dz.emit("thumbnail", mockFile, media.url);
        dz.emit("complete", mockFile);

        mockFile.previewElement.classList.add("dz-success");
        mockFile.previewElement.classList.add("dz-complete");

        mockFile.id = media.id;
        dz.files.push(mockFile);
      });
    },
    removedfile: function (file) {
      console.log(file);
      var deleteEndpoint = '{{ route('admin.product.image') }}';
      var params = { 
        product_uuid: '{{ $product->uuid }}',
        image_id: file.id 
      };
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": $('input[name="_token"]').val()
        }
      });
      $.post(deleteEndpoint, params, function (data) {
        if(data == 'success'){
          file.previewElement.remove();
        }
      });
    },
    success: function (file, response) {
      // Handle successful uploads here
      file.id = response.image_id;
      // console.log(response.image_id);
    },
    error: function (file, errorMessage) {
      // Handle upload errors here
      console.error(file);
      alert(errorMessage);

    }
  };

</script>
@if($continue_step)
<script>
  stepper.to(2);
</script>
@endif

@endpush