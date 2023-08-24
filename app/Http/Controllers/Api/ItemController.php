<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Success',
        //     'payload' => [
        //         'item' => $item,
        //         'qr_code_file' => base64_encode(file_get_contents(public_path() . '\\oke\\'.$item->id.'.pdf'))
        //     ]
        // ]);
    }

    private function generateRandString() {
        $code = Str::random(15);

        if (Item::where('unique_code', $code)->exists()) {
            return generateRandString();
        }

        return $code;
    }
}
