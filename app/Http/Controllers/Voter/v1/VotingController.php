<?php

namespace App\Http\Controllers\Voter\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UtilityElection;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VotingController extends Controller
{
    public function viewElection(Request $request)
    {
        $voterAcct = $request->validate([
            'id' => ['required'],
            'college_init' => ['required', 'string']
        ]);

        if (UtilityElection::hasVoted($voterAcct['id'])) {
            $votes = DB::table('voting_results')
                ->where('voter_id',$voterAcct['id'])
                ->join('candidate_models','voting_results.candidate_id','=','candidate_models.id')
                ->join('position_models','voting_results.position_id','=','position_models.id')
                ->select('voting_results.*','candidate_models.lname','candidate_models.fname','candidate_models.mname','candidate_models.image','position_models.pos_name')
                ->orderBy('voter_id')
                ->get();

            return response([
                'message' => 'You have already voted!',
                'votes'=>$votes,
            ], 202);
        } elseif (UtilityElection::getCollege($voterAcct['college_init'])) {
            $ballotList = DB::table('candidate_models')
                ->where('college_init', $voterAcct['college_init'])
                ->join('position_models', 'candidate_models.position_id', '=', 'position_models.id')
                ->join('partylist_models', 'candidate_models.partylist_id', '=', 'partylist_models.id')
                ->select('candidate_models.*', 'position_models.pos_name', 'partylist_models.party_name')
                ->orderBy('position_id')
                ->get();

            return response([
                'message' => $ballotList,
            ], 200);
        }

        return response([
            'message' => 'There`s no voting event in your college as for now...'
        ], 202);
    }

    //sending arrays of votes?
    public function castVote(Request $request)
    {
        $data = $request->all();

        if ($data) {
            // Fetch candidates to avoid N+1 query problem
            $candidates = DB::table('candidate_models')->whereIn('id', $data)->get();
            $userId = Auth::id();

            foreach($candidates as $candidate) {
                DB::table('voting_results')->updateOrInsert([
                    'voter_id' => $userId,
                    'position_id' => $candidate->position_id,
                    'candidate_id' => $candidate->id,
                    'election_id' => $candidate->election_id
                ]);
            }
            return response([
                'success' => 'Your vote has been casted. Please restart the page!'
            ], 201);
        }

        return response([
            'error' => 'Something went wrong. Please try again later...'
        ], 500);
    }
}
