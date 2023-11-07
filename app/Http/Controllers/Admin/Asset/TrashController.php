<?php

namespace App\Http\Controllers\Admin\Asset;

use App\Http\Controllers\Admin\MasterController;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use DB, DataTables;

class TrashController extends MasterController
{
    protected $modules = [
        'products'
    ];
    /**
     * Handle the incoming request.
     */
    public function index(Request $request, $type)
    {
        if ($request->ajax()) {
            $table = str()->plural($type);
            $data = DB::table($table)->whereNotNull('deleted_at');
            return  DataTables::of($data)
                    ->addColumn('options', function($row) use($type){
                        $btn = '<div class="btn-group">
                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  <a class="dropdown-item" href="'.route('admin.trashed.restore', [$type, $row->uuid]).'">Recover</a>
                                  <a class="dropdown-item dt-delete" data-url="'.route('admin.trashed.delete', [$type, $row->uuid]).'">Delete</a>
                                </div>
                              </div>';
                        return $btn;
                    })
                    ->rawColumns(['options'])
                    ->make(true);
        }
        $this->dt = $this->sa = true;
        $this->title = 'Product Trash';
        return $this->view('trash');
    }

    public function restore($type, $uuid)
    {
        switch ($type) {
            case 'product':
                Product::onlyTrashed()->firstWhere('uuid', $uuid)->restore();
                break;
            default:
                return back()->with('error', 'unknown type '.$type);
                break;
        }

        return to_route('admin.trashed.index', 'products')->with('success','Restored');
    }

    public function PDel($type, $uuid)
    {
        switch ($type) {
            case 'product':
                $product = Product::onlyTrashed()->firstWhere('uuid', $uuid);
                $product->forceDelete();
                $product->media()->delete();
                break;
            default:
                throw new \Exception('Unknown type : '.$type);
                break;
        }
        return back()->withSuccess('Product permanently deleted');
    }
}
