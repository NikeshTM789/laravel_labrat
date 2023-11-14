<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MasterController;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Events\Admin\UserCreateEvent;
use Illuminate\Support\Facades\DB;

class UserController extends MasterController
{

    protected function view($path)
    {
        return parent::view('users.'.$path);
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
            $src   = DB::table('users')
                        ->select(['*','users.id as id'])
                        ->leftjoin('removes', function($join){
                            $join->on('users.id','=','removes.removable_id')
                                ->where('removes.removable_type','App\Models\User');
                        })
                        ->where(function($qry) use($request, $searchable){
                            foreach ($searchable as $item) {
                                $qry->orWhere($item,'LIKE','%'.$request->search['value'].'%');
                            }
                        });
                        // ->where('users.name','LIKE','%'.$request->search['value'].'%');

            $total = $src->count();

            $filtered = $src->orderBy($column, $order);
            $filteredTotal = $filtered->count();
            $pagination    = $filtered->skip($limit_init)->take($to)->get();
            return response()->json(["data" => $pagination, "recordsFiltered" => $filteredTotal, "recordsTotal" => $total]);
        }
        $this->title = 'User list';
        $this->sa = $this->dt = true;
        return $this->view('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->title = 'User create';
        $this->s2 = true;
        return $this->view('create')->with(['file' => true, 'user' => new User]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $message = null;
        try {
            event(new UserCreateEvent($request, $request->role));
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with(['success' => 'An email has been sent to the user']);
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
    public function edit(User $user)
    {
        $this->title = 'User edit';
        $this->s2 = $this->sa = true;
        return $this->view('edit')->with(['file' => true, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        DB::transaction(function () use($user, $request) {
            $formData = ($request->safe()->except('role'));
            tap($user, fn($user) => $user->update($formData))->syncRoles($request->role);
            $user->saveMedia($request->profile);
        });
        return back()->with(['success' => 'User Updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $message = 'User Deleted';
        if($user->trashed()){
            $user->forceDelete();
            $message = 'User deleted permanently';
        }
        $user->delete();
        return back()->withSuccess($message);
    }

    public function restore($user)
    {
        $user = User::onlyTrashed()->findOrFail($user)->restore();
        return back()->with(['success' => 'User restored']);
    }

    public function deleteMedia(User $user)
    {
        $user->getMedia(User::MEDIA_USER)->first()->delete();
        return back()->withSuccess('Media Deleted');
    }
}
