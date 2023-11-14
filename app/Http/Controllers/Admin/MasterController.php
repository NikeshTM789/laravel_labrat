<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Toastr;

class MasterController extends Controller
{
    // use Toastr;

    protected $title = 0, $module = null, $s2 = false, $sa = false, $sm = false, $dt = false;

    protected function view($path)
    {
        $title = $this->title;
        $module = $this->module;
        config(['adminlte.plugins.Select2.active' => $this->s2]);
        config(['adminlte.plugins.Sweetalert2.active' => $this->sa]);
        config(['adminlte.plugins.Summernote.active' => $this->sm]);
        config(['adminlte.plugins.Datatables.active' => $this->dt]);

        return view('admin.'.$path, compact('title','module'));
    }
/*
    protected function delete($row)
    {
        $message = 'Record cannot be restore';
        if ($row->trashed()) {
            $message = 'Record Restored';
            $row->restore();
        }
        return $row;
    }*/
}
