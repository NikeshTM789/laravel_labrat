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
            <x-admin.table-header :module=$module/>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="datatables" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty(Remaining)</th>
                        <th>Price(Discounted)</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th class="disabled-sorting">Options</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Qty(Remaining)</th>
                        <th>Price(Discounted)</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th class="">Options</th>
                    </tr>
                </tfoot>
                <tbody></tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
@stop

@push('js')
<script>
$(document).ready(function() {
  const columns = [{
            data:'name',
            name:'name'
        },{
            data:'quantity',
            name:'quantity',
            searchable:false,
            orderable:false
        },{
            data:'discounted_price',
            name:'discounted_price',
            searchable:false,
            orderable:false
        },{
            data:'status',
            name:'status',
            searchable:false,
            orderable:false
        },{
            data:'featured',
            name:'featured'            ,
            searchable:false,
            orderable:false
        },{
            data:'options',
            name:'options',
            searchable:false,
            orderable:false
        }];

      datatable_configs(columns);
});
</script>
@endpush