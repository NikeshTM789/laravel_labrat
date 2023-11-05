@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('packages/photoviewer/photoviewer.css') }}">
@endpush

@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop


@section('content')

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-text-width"></i>
              Product Details
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-4">Name</dt>
              <dd class="col-sm-8">{{ $product->name }}</dd>
              <dt class="col-sm-4">Category</dt>
              <dd class="col-sm-8">{{ $product->categories->map(fn($i, $k) => $i->name)->join(',',' and ') }}</dd>
              <dt class="col-sm-4">Slug</dt>
              <dd class="col-sm-8">{{ $product->slug }}</dd>
              <dt class="col-sm-4">Quantity</dt>
              <dd class="col-sm-8">{{ $product->quantity }}</dd>
              <dt class="col-sm-4">Price</dt>
              <dd class="col-sm-8">{{ $product->price }}</dd>
              <dt class="col-sm-4">Discounted Price</dt>
              <dd class="col-sm-8">{{ $product->discounted_price }}</dd>
              @if ($product->is_featured)
              <dt class="col-sm-4">Is Featured</dt>
              <dd class="col-sm-8">Yes</dd>
              @endif
              <dt class="col-sm-4">Details</dt>
              <dd class="col-sm-8">{!! $product->details !!}</dd>
              <dt class="col-sm-4">Added By</dt>
              <dd class="col-sm-8">{{ $product->created_by->name }}</dd>
              <dt class="col-sm-4">Gallery</dt>
              <dd class="col-sm-8">
                <a data-gallery="example" href="{{ $product->getFirstMediaUrl('product_featured') }}">
                  <img src="{{ $product->getFirstMediaUrl('product_featured', 'dropzone') }}">
                </a>
                @foreach ($product->getMedia('product_gallery') as $media)
                  <a data-gallery="example" href="{{ $media->getUrl() }}">
                    <img src="{{ $media->getUrl('dropzone') }}">
                  </a>
                @endforeach
              </dd>
            </dl>
          </div>
          <!-- /.card-body -->
        </div>
            <!-- /.card -->
      </div><!--/. container-fluid -->
    </section>

@stop

@push('scripts')
<script src="{{ asset('packages/photoviewer/photoviewer.min.js') }}" type="text/javascript"></script>
<script>
  $('[data-gallery=example]').click(function (e) {
  e.preventDefault();
  var items = [],
    options = {
      index: $(this).index()
    };
  $('[data-gallery=example]').each(function () {
    console.log($(this));
    let src = $(this).attr('href');
    items.push({
      src: src
    });
  });
  new PhotoViewer(items, options);
});
</script>
@endpush