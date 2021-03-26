<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistedItem extends Model
{
    use HasFactory;

    protected $fillable = ['wished_item'];

}
