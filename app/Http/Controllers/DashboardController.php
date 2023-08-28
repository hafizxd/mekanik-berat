<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reparation;
use App\Models\Item;

class DashboardController extends Controller
{
    public function index() {
        $items = Item::paginate(10);

        return view('dashboard.index', compact('items'));
    }

    public function show($id) {
        $item = Item::findOrFail($id);

        $reparations = Reparation::with('user')
            ->where('item_id', $item->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.detail', compact('item', 'reparations'));
    }
}
