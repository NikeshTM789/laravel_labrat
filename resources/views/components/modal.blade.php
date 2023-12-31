<div class="modal fade" id="{{ $modalid }}">
<div class="modal-dialog modal-{{ $size }}">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">{{ $model_title }}</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        {{ $slot }}
    </div>
    <div class="modal-footer justify-content-between">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" form="form-{{ $modalid }}" id="btn-{{ $modalid }}" class="btn btn-primary">Save</button>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->