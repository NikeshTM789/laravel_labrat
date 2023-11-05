<?php

namespace App\Http\Controllers\Admin\Asset;

use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Admin\{Product, Unit, Category};
use DB, DataTables;

class ProductController extends MasterController
{
    protected function view($path)
    {
        $this->module = 'product';
        return parent::view('assets.'.$path);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::select('*');
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('status', function($row){
                        $btn = '<i class="fa fa-check text-success"></i>';
                        if ($row->trashed()) {
                            $btn = '<i class="fa fa-ban text-danger"></i>';
                        }
                        return $btn;
                    })
                    ->editColumn('featured', function($row){
                        $btn = '<i class="fa fa-square text-danger"></i>';
                        if ($row->featured) {
                            $btn = '<i class="fa fa-square text-success"></i>';
                        }
                        return $btn;
                    })
                    ->addColumn('options', function($row){
                        $btn = '<div class="btn-group">
                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  <a class="dropdown-item" href="'.route('admin.product.edit', $row->uuid).'">Edit</a>
                                  <a class="dropdown-item dt-delete" data-url="'.route('admin.product.destroy', $row->uuid).'" href="#">Trash</a>
                                  <a class="dropdown-item" href="'.route('admin.product.show', $row->uuid).'">Show</a>
                                </div>
                              </div>';
                        return $btn;
                    })
                    ->rawColumns(['status','featured','options'])
                    ->make(true);
        }
        $this->datatable = $this->sweetalert = true;
        $this->title = 'Products';
        return $this->view('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->title = 'Create Product';
        $units = Unit::select('id','title')->get();
        $categories = Category::get(['id','name']);
        $this->select2 = $this->summernote = true;

        return $this->view('create')->with(['categories' => $categories, 'product' => new Product, 'units' => $units]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $uuid = null;
        DB::transaction(function () use($request, &$uuid) {
            $product = (tap(Product::create($request->safe()->except(['categories'])), fn($qry) => $qry->categories()->sync($request->categories)));
            $uuid = $product->uuid;
        });
        return to_route('admin.product.edit', $uuid)->with('success','Product added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('media','categories');
        $this->title = 'Show Product';
        return $this->view('show')->with(['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->title = 'Edit Product';
        $units = Unit::select('id','title')->get();
        $categories = Category::get(['id','name']);
        $gallery = $product->getMedia(PRODUCT::GALLERY)->map(function($media){
            return [
                'id' => $media->id,
                'url' => $media->getUrl('dropzone'),
                'size' => $media->size,
                'name' => $media->name
            ];
        });
        $featured = $product->getMedia(PRODUCT::FEATURED)->map(function($media){
            return [
                'id' => $media->id,
                'url' => $media->getUrl('dropzone'),
                'size' => $media->size,
                'name' => $media->name
            ];
        });
        $this->select2 = $this->summernote = true;

        $with = ['categories' => $categories, 'product' => $product, 'units' => $units, 'gallery' => $gallery, 'featured' => $featured];

        if (str_contains(url()->previous(), 'admin/product/create')) {
            $with['continue_step'] = true;
        }
        return $this->view('edit')->with($with);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        DB::transaction(function () use($request, $product){
            tap($product, function($product) use($request){
                $product->update($request->safe()->except(['categories']));
            })->categories()->sync($request->categories);
        });
        return to_route('admin.product.index')->with('success','Product updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return to_route('admin.product.index')->with('success','Product trashed');
    }

    public function product_image(Request $request)
    {
        $product = Product::whereUuid($request->product_uuid)->firstOrFail();
        if ($request->has('image_id')) {
            $product->media()->find($request->image_id)->delete();
            return response('success');
        }else{
            if ($request->has('gallery')) {
                $product->saveGallery();
                $image_id = $product->getMedia(Product::GALLERY)->last()->id;
            }elseif ($request->has('featured')) {
                $product->saveFeatured();
                $image_id = $product->getMedia(Product::FEATURED)->last()->id;
            }
            return response([
                'image_id' => $image_id
            ], $status = 200);
        }
    }

    public function delete_product_image()
    {
        
    }
}
