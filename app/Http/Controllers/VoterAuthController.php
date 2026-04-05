<?php

namespace App\Http\Controllers;

use App\Models\VoterAcctModel;
use App\Models\VoterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoterAuthController extends Controller
{

    //vote login
    public function voterLogin(Request $request)
    {
        $credentials = $request->validate([
            'idNum' => ['required'],
            'password' => ['required']
        ]);

        if (!Auth::guard('voter')->attempt($credentials)) {
            return response([
                'errors' => 'Wrong inputs.Please try again or register your account.'
            ], 422);
        }

        $voter = Auth::guard('voter')->user();
        $token = $voter->createToken('main', ['access-voter'])->plainTextToken;

        return response([
            'voter' => $voter,
            'token' => $token
        ], 200);
    }

    //voter logout
    public function voterLogout(Request $request)
    {
        Auth::guard('voter')->logout();
        $request->user()->currentAccessToken()->delete();

        return response([
            'success' => 'You have successfully logged out. Please log-in again!'
        ], 200);
    }

    //creating here the voter endpoint requires the voter_models, voter_acct_models table
    public function voterCreateAcct(Request $request)
    {
        $data = $request->validate([
            'idNum' => ['required'],
            'email' => ['required', 'email', 'string', 'unique:voter_acct_models,email'],
            'college_init' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $preRegVoter = DB::table('voter_models')
            ->where('idNum', $data['idNum'])
            ->first();

        if ($preRegVoter) {
            DB::table('voter_acct_models')->insert([
                'idNum' => $data['idNum'],
                'email' => $data['email'],
                'fname' => $preRegVoter->fname,
                'lname' => $preRegVoter->lname,
                'college_init' => $data['college_init'],
                'password' => bcrypt($data['password'])
            ]);

            DB::table('voter_models')
                ->where('idNum', $data['idNum'])
                ->delete();

            return response([
                'success' => 'You are now registered! Please login to continue.'
            ], 201);
        } else {
            return response([
                'errors' => 'You are not authorized or you have been already registered! Please contact MIS.'
            ], 401);
        }
    }

    //get the voter session
    public function getVoterSession(Request $request): \Illuminate\Http\JsonResponse
    {
        $voterInfo = $request->user();

        return response()->json($voterInfo,200);
    }

    //single search of voter record (optional feature)
    public function getVoterInfo($idNum)
    {
        $voter = DB::table('voter_acct_models')->where('idNum', $idNum)->first();

        if (!$voter) {
            return response([
                'error' => 'This student ID is not existing from the origin server!'
            ], 422);
        }
        return response([
            'student' => $voter
        ], 200);
    }
}
