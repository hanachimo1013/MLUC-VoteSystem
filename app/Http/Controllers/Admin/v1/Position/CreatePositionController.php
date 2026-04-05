<?php

namespace App\Http\Controllers\Admin\v1\Position;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use App\Models\Admin\PositionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreatePositionController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'name' => ['required']
        ]);

        if ($data) {
            try {
                DB::table('position_models')
                    ->insert([
                        'pos_name' => $data['name']
                    ]);
                return response([
                    'success' => 'Position has been inserted. Please restart the page!'
                ], 201);
            } catch (\Throwable $e) {
                return response([
                    'error' => 'Something error in your input. Please try again by refreshing this page!'
                ], 422);
            }
        }
        return response([
            'error' => 'Something went wrong. Please try again later!'
        ], 500);
    }
}
