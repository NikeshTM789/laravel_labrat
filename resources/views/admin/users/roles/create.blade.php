@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col">
            <!-- general form elements -->
            <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @component('admin.users.roles.form', ['role' => $role, 'permissions' => $permissions, 'role_permissions' => $role_permissions, 'action' => route('admin.role.store')])
              @endcomponent
            </div>
            <!-- /.card -->

          </div>
          <!--/.col (left) -->
        </div>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
@stop

@push('scripts')
<script type="text/javascript">
const custom_select = "{{ old('role','') }}";
select2('Select a role',false,custom_select);
</script>
@endpush