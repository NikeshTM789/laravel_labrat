<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Unit;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia, MediaCollections\Models\Media};
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    const MEDIA_FEATURED = 'product_featured';
    const MEDIA_GALLERY = 'product_gallery';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'quantity',
        'price',
        'unit_id',
        'discounted_price',
        'featured',
        'details'
    ];

    public function getRouteKeyName() {
        return 'uuid';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function($product) {
            $product->uuid = str()->uuid();
            // $product->slug = str()->slug(request()->name);
            $product->slug = substr(md5(uniqid(rand(), true)), 0, 10);
            $product->added_by = auth()->id();
        });
        static::deleting(function($product) { # soft delete too
        });
    }

    public function registerMediaCollections(Media $media = null): void
    {
        $collection_A = $this->addMediaCollection(self::MEDIA_FEATURED)
                        ->useDisk('media')
                        ->acceptsMimeTypes(['image/jpeg','image/png'])
                        ->singleFile();
        $collection_B = $this->addMediaCollection(self::MEDIA_GALLERY)
                        ->useDisk('media')
                        ->acceptsMimeTypes(['image/jpeg','image/png']);
        $this->commonProps($collection_A);
        $this->commonProps($collection_B);
    }

    private function commonProps($mediaRef)
    {
        $mediaRef->registerMediaConversions(function (Media $media) {
            $this->addMediaConversion('dropzone')
                ->crop('crop-center', 120, 120);
            $this->addMediaConversion('thumbnail')
                ->width(500)
                ->height(500);
        });
    }

    public function saveCollection($file, $collection)
    {
        $this->addMedia($file)->toMediaCollection($collection);
    }


    public function saveGallery()
    {
        $this->saveCollection(request()->file('file'), self::GALLERY);
    }

    public function saveFeatured()
    {
        $this->saveCollection(request()->file('file'), self::FEATURED);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class,'added_by','id');
    }
}
