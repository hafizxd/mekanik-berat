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

    protected $appends = ['latest_status'];

    public function reparations() {
        return $this->hasMany(Reparation::class);
    }

    public function scans() {
        return $this->hasMany(Scan::class);
    }

    protected function getLatestStatusAttribute() {
        $reparation = Reparation::where('item_id', $this->id)->orderBy('created_at', 'desc')->select('status')->first();

        if (isset($reparation)) {
            return $reparation->status;
        }

        return 1;
    }
}
