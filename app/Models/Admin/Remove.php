<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remove extends Model
{
    use HasFactory;

    public function removable()
    {
        return $this->morphTo();
    }
}
