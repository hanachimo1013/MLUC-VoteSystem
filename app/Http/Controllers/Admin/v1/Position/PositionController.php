<?php

namespace App\Http\Controllers\Admin\v1\Position;

use App\Http\Controllers\Controller;
use App\Models\Admin\PositionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    public function index()
    {
        $data = DB::table('position_models')
            ->get();

        if ($data) {

            return response([
                'success' => $data
            ], 200);
        }
        return response([
            'error' => 'Something went wrong. Please try again later!'
        ]);
    }

    public function deletePosition(Request $request)
    {
        $data = $request->only('id');

        if ($data) {
            try {
                DB::table('position_models')
                    ->where('id', $data['id'])
                    ->delete();
                return response([
                    'success' => 'Position has been deleted. Please restart the page!'
                ], 201);
            } catch (\Throwable $e) {
                return response([
                    'success' => 'Cannot deleted! Check inputs or please restart the page!'
                ], 422);
            }
        }
        return response([
            'error' => 'Something went wrong. Please try again later!'
        ], 500);
    }
}
