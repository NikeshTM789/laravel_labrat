<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubComment;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body'
    ];

    public function subComments()
    {
        return $this->hasMany(SubComment::class);
    }
}
