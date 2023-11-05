<form method="POST" action="{{ $action }}">
  @csrf
  {{ $slot }}
  <div class="card-body">
    <div class="form-group">
      <label for="exampleInputEmail1">Title</label>
      <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Role Name" value="{{ $role->name ?? old('name') }}">
      @error('name')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>
    <!-- /.col -->
    <x-adminlte-select2 class="select2" name="permissions[]" label="Permissions" data-placeholder="Select permissions..." :config="['allowClear' => true]" multiple>
        @foreach ($permissions as $permission)
        <option value="{{ $permission->id }}" @selected($role_permissions->contains($permission->id))>{{ $permission->name }}</option>
        @endforeach
        @error('permissions')
          <div class="text-danger">{{ $message }}</div>
        @enderror
    </x-adminlte-select2>
    <!-- /.col -->
  </div>
  <!-- /.card-body -->

  <div class="card-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>

@push('js')
<script>
select2();
</script>
@endpush