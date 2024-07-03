<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function getKategori()
    {
        $kategori = Kategori::all();
        return response()->json([
            'status' => 200,
            'kategori' => $kategori
        ], 200);
    }

    public function createKategori(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'name' => 'required',
                'desc' => 'required'
            ]);

            if ($validasi->fails()) {
                return response()->json([
                    'status' => 403,
                    'message' => $validasi->errors()
                ], 403);
            }

            $kategori = Kategori::create([
                'name' => $request->name,
                'desc' => $request->desc
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Kategori berhasil ditambahkan',
                'kategori' => $kategori
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateKategori(Request $request, Kategori $kategori)
    {
        try {
            if ($kategori) {
                $validasi = Validator::make($request->all(), [
                    'name' => 'string',
                    'desc' => 'string'
                ]);

                if ($validasi->fails()) {
                    return response()->json([
                        'status' => 403,
                        'message' => $validasi->errors()
                    ], 403);
                }

                $kategori->update([
                    'name' => $request->name,
                    'desc' => $request->desc
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Kategori berhasil diubah',
                    'kategori' => $kategori
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteKategori(Kategori $kategori)
    {
        try {
            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Kategori berhasil dihapus'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
