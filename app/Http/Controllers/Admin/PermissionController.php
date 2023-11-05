<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MasterController;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PermissionController extends MasterController
{

    protected function view($path)
    {
        return parent::view('users.permissions.'.$path);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = $searchable = [];
            foreach ($request->columns as $item) {
                array_push($columns, $item['name']);
                if ($item['searchable'] == 'true') {
                    array_push($searchable, $item['name']);
                }
            }
            $column     = request()->order[0]['column'] ? $columns[request()->order[0]['column']] : $columns[0];
            $order      = request()->order[0]['dir'];
            $limit_init = request('start');
            $to         = request('length');

            $src   = DB::table('permissions')
                    ->where(function($qry) use($request, $searchable){
                                    foreach ($searchable as $item) {
                                        $qry->orWhere($item,'LIKE','%'.$request->search['value'].'%');
                                    }
                                });

            $total = $src->count();

            $filtered = $src->orderBy($column, $order);
            $filteredTotal = $filtered->count();
            $pagination    = $filtered->skip($limit_init)->take($to)->get();
            return response()->json(["data" => $pagination, "recordsFiltered" => $filteredTotal, "recordsTotal" => $total]);
        }
        $this->title = 'Permission list';
        $this->datatable = $this->sweetalert = true;
        return $this->view('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {

            $request->validate([
                'permission' => 'required|unique:permissions,name'
            ],[
                'permission.required' => 'Permission name is required'
            ]);
            DB::transaction(function () use($request){
                Permission::create(['name' => $request->permission]);
            });
            return response(['message' => 'Permission added'], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->update(['name' => request('permission')]);
        return to_route('admin.permissions.index')->with(['success' => 'Permission updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        if($permission->id <= 9){
            return to_route('admin.permission.index')->with(['error' => 'Permission cannot be removed']);
        }
        $permission->delete();
        return to_route('admin.permission.index')->with(['success' => 'Permission removed']);
    }
}
