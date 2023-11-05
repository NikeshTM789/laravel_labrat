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
        <div style="width:fit-content;float:right;">
          <a type="link" href="{{ route('admin.user.create') }}" class="btn bg-gradient-success">Create</a>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="datatables" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Verified</th>
                    <th>Blocked</th>
                    <th class="disabled-sorting">Actions</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Verified</th>
                    <th>Blocked</th>
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

@section('js')
<script>
$(document).ready(function() {
  const configs = [{
            data: 'name',//recerved
            name: 'users.name'// DB column name
        }, {
            data: 'email_verified_at',
            name: 'email_verified_at',
            sortable: false,
            searchable: false,
            render: function(data,type, row){
              return (data) ? data : 'No';
            }
        }, {
            data: 'deleted_at',
            sortable: false,
            searchable: false,
            render: function(data,type, row){
              return (data) ? ((data.removable_id) ? 'YES + PD' : 'YES') : 'NO';
            }
        }, {
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
                        '<a class="dropdown-item" href="'+(window.location.href.split('#')[0]+'/'+row.id+'/edit')+'">Edit</a>';
                    if (row.deleted_at) {
                        options +='<a class="dropdown-item" href="'+window.location.href.split('#')[0]+'/restore/'+row.id+'">Restore</a>';
                        if(!row.removable_id){
                            options += '<a class="dropdown-item dt-delete" href="#" data-url="'+window.location.href.split('#')[0]+'/'+row.id+'">Delete Permanently</a>';
                        }
                    }else{
                        options +='<a class="dropdown-item dt-delete" data-url="'+window.location.href.split('#')[0]+'/'+row.id+'" href="#">Delete</a>';
                    }
                        options += '<a class="dropdown-item" href="#">Show</a>'+
                        '</div>'+
                        '</div>';
                        return options;
            }
        }];
  datatable_configs(configs);
});
</script>
@stop