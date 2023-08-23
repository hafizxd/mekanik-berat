<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function list() {
        $items = auth()->user()->items();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => $items
        ]);   
    }

    public function show($id) {
        $item = Item::findOrFail($id);

        if (!isset($item->mekanik)) {
            $item->update([
                'mekanik_id' => auth()->user()->id
            ]);
        }
        
        $item->isMaintained = $item->mekanik_id == auth()->user()->id ? true : false;

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => $item
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'type' => 'required',
            'hours_meter' => 'required|numeric',
            'capacity' => 'required|numeric',
            'engine' => 'required',
            'lifting_height' => 'required|numeric',
            'stage' => 'required|numeric',
            'load_center' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation fails.',
                'payload' => [
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        $item = Item::create([
            'jenis' => $request->jenis,
            'type' => $request->type,
            'hours_meter' => $request->hours_meter,
            'capacity' => $request->capacity,
            'engine' => $request->engine,
            'lifting_height' => $request->lifting_height,
            'stage' => $request->stage,
            'load_center' => $request->load_center,
            'status' => 1,
        ]);

        $pdf = Pdf::loadView('pdf.qr-code', compact('item'));
        return $pdf->download('invoice.pdf');
    }
}
