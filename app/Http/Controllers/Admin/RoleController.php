<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Spatie\Permission\Models\{Role, Permission};

class RoleController extends MasterController
{

    protected function view($path)
    {
        return parent::view('users.roles.'.$path);
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

            $src   = DB::table('roles')
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
        $this->title = 'Role list';
        $this->datatable = $this->sweetalert = true;
        return $this->view('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->title = 'Role Create';
        $permissions = Permission::get(['id', 'name']);
        $role_permissions = collect([]);
        $this->select2 = true;
        return $this->view('create')->with(['role' => new Role, 'permissions' => $permissions, 'role_permissions' => $role_permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','unique:roles,name'],
            'permissions'=> ['nullable','array']
        ]);
        Role::create($request->only('name'))->syncPermissions($request->permissions);
        return back()->withSuccess('Role Added');
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
    public function edit(Role $role)
    {
        $this->title = 'Role Edit';
        $permissions = Permission::get(['id', 'name']);
        $this->select2 = true;
        $role_permissions= $role->permissions->pluck('id');
        return $this->view('edit')->with(['select2' => true, 'role' => $role, 'permissions' => $permissions, 'role_permissions' => $role_permissions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required','unique:roles,name,'.$role->id],
            'permissions'=> ['nullable','array']
        ]);

        tap($role, function($role)use($request){
            $role->update($request->only('name'));
        })
        ->syncPermissions($request->permissions);

        return back()->withSuccess('Role Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->id <= 3) {
            return to_route('admin.roles.index')->with(['error' => 'Role cannot be deleted']);
        }
        $role->delete();

        return to_route('admin.role.index')->with(['success' => 'Role deleted']);
    }
}
