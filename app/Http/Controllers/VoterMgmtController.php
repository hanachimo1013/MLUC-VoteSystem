<?php

namespace App\Http\Controllers;

use App\Models\VoterAcctModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\VoterModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class VoterMgmtController extends Controller
{
    //single search of voter record (optional feature)
    public function getVoterInfo($idNum)
    {
        $voter = DB::table('voter_models')->where('idNum',$idNum)->first();

        if (!$voter) {
            return response([
                'errors' => 'This student ID is not existing from the origin server!'
            ],404);
        }
        return response([
            'success'=>'Data Loaded!',
            'student' => $voter
        ], 200);
    }

    //fetch all registered voters (table:voter_acct_models)
    public function getVoterInfoAll()
    {
        $voterInfo = VoterAcctModel::all();

        if (!$voterInfo) {
            return response([
                'error' => 'No registered voters!'
            ], 422);
        }
        return response([
            'students' => $voterInfo
        ], 200);
    }

    //updating voter's data (tool)
    public function updateVoterData(Request $request)
    {
        $data = $request->validate([
            'idNum'=>['required'],
            'fname' => ['required'],
            'lname' => ['required'],
            'college_init' => ['required'],
            'password' => ['required']
        ]);

        if ($data) {
            if (UtilityElection::findVoter($data['idNum'])){
                DB::table('voter_models')
                    ->upsert([
                        [
                            'idNum'=> $data['idNum'],
                            'fname' => $data['fname'],
                            'lname' => $data['lname'],
                            'college_init' => $data['college_init'],
                            'password' => bcrypt($data['password'])]
                    ], ['idNum'], ['fname','lname','college_init','password']);

                return response([
                    'success'=>'The voter`s data has been updated. Please restart the page...'
                ],201);
            }
            else{
                return response([
                    'error'=>'The voter`s is not existing in the system!'
                ],404);
            }
        }

        return response([
            'error'=>'Something went wrong. Please try again later!'
        ], 500);
    }

    public function deleteVoterData(Request $request){
        $data = $request->all();

        if ($data){
            DB::table('voter_acct_models')
                ->where('id',$data['id'])
                ->delete();

            return response([
                'success'=>'Voter`s data has been deleted. Please refresh the page!'
            ], 200);
        }

        return response([
            'error'=>'Something went wrong. Please try again later'
        ]);
    }
}
