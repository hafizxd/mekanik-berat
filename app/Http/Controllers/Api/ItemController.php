<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Reparation;
use App\Models\Item;
use App\Models\Scan;

class ItemController extends Controller
{
    public function list() {
        $scans = auth()->user()->scans()->with('item')->get();

        $items = [];
        foreach ($scans as $scan) {
            $items[] = $scan->item;
        }

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => $items
        ]);
    }

    public function show($id) {
        $item = Scan::where('user_id', auth()->user()->id)->where('item_id', $id)->firstOrFail()->item;

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => $item
        ]);
    }

    public function scan(Request $request) {
        $validator = Validator::make($request->all(), [
            'unique_code' => 'required'
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

        $item = Item::where('unique_code', $request->unique_code)->first();

        if (!isset($item)) {
            return response()->json([
                'success' => false,
                'message' => 'Alat mekanik tidak ditemukan',
                'payload' => []
            ], 404);
        }

        if ($item->scans()->where('user_id', auth()->user()->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Alat mekanik sudah pernah ditambahkan',
                'payload' => []
            ], 400);
        }

        $item->scans()->create([ 'user_id' => auth()->user()->id ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan alat mekanik',
            'payload' => []
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'type' => 'required',
            'hours_meter' => 'required|numeric',
            'capacity' => 'required|numeric',
            'engine' => 'required',
            'lifting_height' => 'nullable|numeric',
            'stage' => 'nullable|numeric',
            'load_center' => 'nullable|numeric'
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
            'unique_code' => $this->generateRandString()
        ]);

        $pdf = Pdf::loadView('pdf.qr-code', compact('item'));
        return $pdf->download('qr_code.pdf');
    }

    public function delete($id) {
        $item = Item::findOrFail($id);

        $exists = auth()->user()->scans()->where('item_id', $id)->exists();
        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak boleh menghapus alat yg belum discan',
                'payload' => []
            ], 403);
        }

        $item->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus alat mekanik',
            'payload' => []
        ]);
    }

    private function generateRandString() {
        $code = Str::random(15);

        if (Item::where('unique_code', $code)->exists()) {
            return generateRandString();
        }

        return $code;
    }



    // Reparation
    public function listReparation($itemId) {
        $itemExists = auth()->user()->scans()->where('item_id', $itemId)->exists();

        if (! $itemExists) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
                'payload' => []
            ], 404);
        }

        $reparations = Reparation::with(['user'])
            ->where('item_id', $itemId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data reparasi',
            'payload' => $reparations
        ]);        
    }

    public function showReparation($itemId, $reparationId) {
        $itemExists = auth()->user()->scans()->where('item_id', $itemId)->exists();

        if (! $itemExists) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
                'payload' => []
            ], 404);
        }

        $reparation = Reparation::with(['user'])
            ->where('id', $reparationId)
            ->where('item_id', $itemId)
            ->first();

        if (!isset($reparation)){
            return response()->json([
                'success' => false,
                'message' => 'Reparation not found.',
                'payload' => []
            ], 404);
        } 

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data reparasi',
            'payload' => $reparation
        ]);        
    }

    public function storeReparation($itemId, Request $request) {
        $validator = Validator::make($request->all(), [
            'hours_meter' => 'required|numeric',
            'note' => 'nullable'
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

        $itemExists = auth()->user()->scans()->where('item_id', $itemId)->exists();

        if (! $itemExists) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
                'payload' => []
            ], 404);
        }

        $reparation = Reparation::create([
            'user_id' => auth()->user()->id,
            'item_id' => $itemId,
            'hours_meter' => $request->hours_meter,
            'note' => $request->note,
            'status' => 2
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reparasi berhasil dibuat',
            'payload' => $reparation
        ]);
    }

    public function updateReparation($itemId, $reparationId) {
        $itemExists = auth()->user()->scans()->where('item_id', $itemId)->exists();

        if (! $itemExists) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
                'payload' => []
            ], 404);
        }

        $reparation = Reparation::with(['user'])
            ->where('id', $reparationId)
            ->where('item_id', $itemId)
            ->first();

        if (!isset($reparation)){
            return response()->json([
                'success' => false,
                'message' => 'Reparation not found.',
                'payload' => []
            ], 404);
        }

        $reparation->update([ 'status' => 1 ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah status reparasi',
            'payload' => $reparation
        ]);        
    }
}
