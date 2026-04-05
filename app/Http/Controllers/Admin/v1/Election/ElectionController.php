<?php

namespace App\Http\Controllers\Admin\v1\Election;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UtilityElection;
use App\Models\Admin\CandidateModel;
use App\Models\ElectionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function response;

class ElectionController extends Controller
{
    public function search()
    {
        $data = ElectionModel::all();

        if ($data) {
            return response([
                'success' => $data
            ], 200);
        }
        return response([
            'error' => 'Something went wrong. Please try again later'
        ], 500);
        //please add the number of candidates that participated in election later
    }

    //election creation
    public function createElection(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'college_init' => ['required']
        ]);

        if ($data) {
            try {
                DB::table('election_models')->insert([
                    'elec_name' => $data['name'],
                    'college_init' => $data['college_init']
                ]);
                return response([
                    'success' => 'Election has been inserted! Please refresh the page!',
                ], 201);
            } catch (\Throwable $exception) {
                return response()->json([
                    'error' => 'Something went wrong. Please try again later!'
                ], 500);
            }
        }
        return response([
            'error' => 'Something went wrong. Please try again later!'
        ], 500);
    }

    public function deleteElection(Request $request)
    {
        $data = $request->only('id');

        if ($data) {
            try {
                DB::table('election_models')
                    ->where('id', $data['id'])
                    ->delete();
                return response([
                    'success' => 'Election has been deleted! Please refresh the page later',
                ], 200);
            } catch (\Throwable $exception) {
                return response()->json([
                    'error' => 'Something went wrong. Please refresh the page later'
                ], 500);
            }
        }
        return response([
            'error' => 'Something went wrong. Please restart the page'
        ], 500);
    }

    public function electionStatusUpdate(Request $request)
    {
        $data = $request->all();

        if (UtilityElection::getActiveElection($data['id']) === 1) {
            DB::table('election_models')
                ->where('id', $data['id'])
                ->update(['status' => 0]);
            return response([
                'success'=>'Status set to inactive. Please restart the page.'
            ],201);
        } elseif (UtilityElection::getActiveElection($data['id']) === 0) {
            DB::table('election_models')
                ->where('id', $data['id'])
                ->update(['status' => 1]);return response([
                'success'=>'Status set to active. Please restart the page.'
            ],201);
        }

        return response([
            'error'=>'Something went wrong. Please try again later'
        ],500);
    }

}
