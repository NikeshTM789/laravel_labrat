<?php

namespace App\Http\Controllers\Admin\Asset;

use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use DB;

class CategoryController extends MasterController
{

    protected function view($path)
    {
        return parent::view('assets.categories.'.$path);
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

            $src   = DB::table('categories')
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
        $this->title = 'Category list';
        $this->dt = $this->sa = true;
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:categories,name'
            ],[
                'name.required'=> 'category name is required'
            ]);

            if ($validator->fails()) {
                // $errorMessages = $validator->errors()->all();
                $errorMessages = $validator->errors()->getMessages();
                $errorMessage = (reset($errorMessages))[0];

                if (count($errorMessages) > 1) {
                    $errorMessage.=' (and '.count($errorMessages).' more error)';
                }

                return response(['message' => $errorMessage, 'errors' => $errorMessages], 422);
            }
            Category::create($request->only('name'));
            return response(['message' => 'Category added'], 201);
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
    public function update(Request $request, Category $category)
    {
        $category->update($request->only('name'));
        return back()->with(['success' => 'Category updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $row = Category::withTrashed()->find($id);
        if ($row->trashed()) {
            $status = 'restored';
            $row->restore();
        }else{
            $status = 'disabled';
            $row->delete();
        }
        return back()->with(['success' => 'category '.$status]);
    }
}
