<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reparation;
use App\Models\Scan;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reparations() {
        return $this->hasMany(Reparation::class);
    }

    public function scans() {
        return $this->hasMany(Scan::class);
    }
}
