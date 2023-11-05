<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name'
    ];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::saving(function($category){
            $category->uuid = str()->uuid();
        });
    }
}
