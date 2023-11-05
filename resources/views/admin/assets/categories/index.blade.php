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
                <form class="d-flex">
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="New Category" />
                    <button type="submit" onclick="handleSubmit(event)" class="btn btn-xs btn-outline-info">ADD</button>
                </form>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="datatables" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Active</th>
                        <th class="disabled-sorting">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Active</th>
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
    <x-Modal :modalid="'category_modal'">
        <form method="POST" id="form-category_modal">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="category">Category Name</label>
                <input type="text" name="name" class="form-control" id="category" placeholder="Enter Category">
            </div>
        </form>
    </x-Modal>

@stop

@push('js')
<script>

var Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 10000
});

$(document).ready(function() {
  const columns = [{
            data: 'name',
            name:'name'
        },
        {
          data:'deleted_at',
          searchable: false,
          render: function(data, type, row){
            let active = 'Y';
            if (row.deleted_at) {
              active = 'N';
            }
            return active;
          }
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
                        '<a class="dropdown-item dt-edit" data-name="'+row.name+'" data-url="'+(window.location.href.split('#')[0]+'/'+row.id)+'" href="#">Edit</a>';
                        if (row.deleted_at) {
                            options += '<a class="dropdown-item dt-delete" data-url="'+window.location.href.split('#')[0]+'/'+row.id+'" href="#">Enable</a>';
                        }else{
                            options += '<a class="dropdown-item dt-delete" data-url="'+window.location.href.split('#')[0]+'/'+row.id+'" href="#">Disabled</a>';
                        }
                        options += '</div>';
                        return options;
            }
        }];
        const edit_modal = function () {
            const modal = document.querySelector('#category_modal');
            const edit_form = modal.querySelector('form');
            document.querySelectorAll('.dt-edit').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    let dataset = e.target.dataset;
                    edit_form.setAttribute('action', dataset.url);
                    edit_form.querySelector('input[name="name"]').value = dataset.name;
                    $(modal).modal('show');
                });
            });
        }

      datatable_configs(columns, [edit_modal]);
});

function handleSubmit(event){
    event.preventDefault();
    let btn = event.target;
    // console.log(btn);
    sendPostAjaxRequest(btn);
}
</script>
@endpush