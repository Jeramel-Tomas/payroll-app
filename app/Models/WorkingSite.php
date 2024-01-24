<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'created_at',
        'updated_at',
    ];
}
