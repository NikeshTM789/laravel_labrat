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
            <h3 class="card-title">DataTable with minimal features & hover style</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="datatables" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="disabled-sorting">Options</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
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
<script src="{{ asset('AdminLTE/plugins/sweetalert2/sweetalert2.min.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function() {
  const columns = [{
            data:'name',
            name:'name'
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