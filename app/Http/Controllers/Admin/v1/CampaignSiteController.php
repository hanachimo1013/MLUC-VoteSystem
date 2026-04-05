<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Models\Admin\CandidateModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignSiteController extends Controller
{
    public function fetchCandidateData()
    {
        $header = DB::table('election_models')
            ->get();

        $data = DB::table('candidate_models')
            ->orderBy('position_id')
            ->join('position_models', 'candidate_models.position_id', '=', 'position_models.id')
            ->join('election_models', 'candidate_models.election_id', '=', 'election_models.id')
            ->join('partylist_models', 'candidate_models.partylist_id', '=', 'partylist_models.id')
            ->join('colleges_models', 'candidate_models.college_init', '=', 'colleges_models.initials')
            ->select('candidate_models.*', 'position_models.pos_name', 'partylist_models.party_name', 'colleges_models.coll_name','election_models.elec_name')
            ->orderBy('candidate_models.position_id')
            ->get();

        try {
            if ($data) {
                return response([
                    'title' => $header,
                    'data'=> $data
                ], 200);
            }
        } catch (\Throwable $e) {
            return response([
                'error' => 'Something went wrong. Please try again later or reload the page!'
            ], 500);
        }
        return response([
            'error' => 'Something went wrong. Please try again later or reload the page!'
        ], 500);
    }
}
