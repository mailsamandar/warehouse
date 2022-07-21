<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedMaterials extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'warehouse_id'];
}
