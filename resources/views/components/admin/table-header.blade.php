  <div class="card-header">
    <h3 class="card-title">{{ $title ?? ucfirst($module.' List') }}</h3>
    <div style="width:fit-content;float:right;">
        <a href="{{ route('admin.'.$module.'.create') }}" class="btn btn-outline-success">Add</a>
        <a href="{{ route('admin.trashed.index', $module) }}" class="btn btn-outline-warning">Trashed</a>
    </div>
  </div>