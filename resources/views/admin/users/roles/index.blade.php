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
            <div style="width:fit-content;float:right;">
              <a type="link" href="{{ route('admin.role.create') }}" class="btn bg-gradient-success">Create</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="datatables" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="disabled-sorting">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th class="">Actions</th>
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
  const configs = [{
            data: 'name',
            name:'name'
        },
        {
            data: null,
            sortable: false,
            searchable: false,
            render: function(data, type, row) {
                let options = '<div class="btn-group">'+
                        '<button type="button" class="btn btn-light">Action</button>'+
                        '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">'+
                        '<span class="sr-only">Toggle Dropdown</span>'+
                        '</button>'+
                        '<div class="dropdown-menu" role="menu">'+
                        '<a class="dropdown-item" href="'+(window.location.href.split('#')[0]+'/'+row.id+'/edit')+'">Edit</a>'+
                        '<a class="dropdown-item dt-delete" data-url="'+window.location.href.split('#')[0]+'/'+row.id+'" href="#">Delete</a>';
                        '</div>';
                        return options;
            }
        }];
  datatable_configs(configs);
});
</script>
@endpush