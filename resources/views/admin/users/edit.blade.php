@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
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
          @component('admin.users.form', ['user' => $user, 'action' => route('admin.user.update', $user->id)])
            @method('PATCH')
          @endcomponent
        </div>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
@stop