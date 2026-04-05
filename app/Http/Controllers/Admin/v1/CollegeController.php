<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Models\Admin\CollegesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollegeController extends Controller
{
    public function index()
    {
        $data = CollegesModel::all();

        if ($data) {
            return response([
                'success' => $data,
            ], 200);
        }

        return response([
            'success' => 'Something went wrong. Please try again later!'
        ], 500);
    }

    public function createCollege(Request $request)
    {
        $data = $request->all();

        if ($data) {
            DB::table('colleges_models')->insert([
                'coll_name' => $data['coll_name'],
                'initials' => $data['initials']
            ]);

            return response([
                'success' => 'College has been inserted!'
            ], 201);
        }

        return response([
            'error' => 'Something went wrong. Please try again later!'
        ]);
    }

    public function sortedDataForColleges()
    {
        $data = DB::table('colleges_models')
            ->join('voter_models', 'colleges_models.initials', '=', 'voter_models.college_init')
            ->select(DB::raw('voter_models.college_init,colleges_models.id,colleges_models.coll_name,colleges_models.initials,count(*) as registered'))
            ->groupBy('voter_models.college_init', 'colleges_models.coll_name')
            ->orderBy('registered', 'asc')
            ->get();

        if ($data) {
            return response([
                'success' => $data,
            ], 200);
        }

        return response([
            'success' => 'Something went wrong. Please try again later!'
        ], 500);
    }

    public function deleteCollegeRecord(Request $request){
        $data = $request->all();

        if ($data){
            DB::table('colleges_models')
                ->where('id',$data['id'])
                ->delete();

            return response([
                'success'=>'The college record has been deleted!'
            ]);
        }

        return response([
            'error'=>'Something went wrong. Please try again later!'
        ]);
    }
}
