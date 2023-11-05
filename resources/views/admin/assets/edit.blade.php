@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Quick Example</h3>
          </div>
          <!-- /.card-header -->

          <!-- form start -->
          @component('admin.assets.form', ['gallery' => $gallery, 'featured' => $featured, 'continue_step' => isset($continue_step), 'update' => true, 'product' => $product, 'categories' => $categories, 'units' => $units, 'action' => route('admin.product.update', $product->uuid)])
          @endcomponent
        </div>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->

@stop
