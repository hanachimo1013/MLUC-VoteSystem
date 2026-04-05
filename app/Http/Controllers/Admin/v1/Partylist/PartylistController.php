<?php

namespace App\Http\Controllers\Admin\v1\Partylist;

use App\Http\Controllers\Controller;
use App\Models\Admin\PartylistModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function response;

class PartylistController extends Controller
{
    public function index(){
        $data = PartylistModel::all();

        if ($data){
            try{
                return response([
                    'success'=>$data
                ], 200);
            }catch(\Throwable $e){
                return response([
                    'error'=>'Something went wrong. Please try again later!'
                ], 500);
            }
        }
        return response([
            'error'=>'Something went wrong. Please try again later!'
        ], 500);
    }

    public function createPartylist(Request $request){
        $data = $request->only('name');

        if ($data){
            try{
                DB::table('partylist_models')->insert([
                    'party_name'=>$data['name'],
                ]);
                return response([
                    'success'=>'Partylist has been added! Please restart the page!'
                ], 201);
            }catch(\Throwable $e){
                return response([
                    'error'=>'Please check your inputs or restart the page!'
                ], 422);
            }
        }
        return response([
            'error'=>'Something went wrong. Please try again later!'
        ], 500);
    }

    public function deletePartylist(Request $request){
        $data = $request->only('id');

        if ($data){
            try{
                DB::table('partylist_models')
                    ->where('id',$data['id'])
                    ->delete();
                return response([
                    'success'=>'Partylist has been deleted! Please restart the page!'
                ], 200);
            }catch(\Throwable $e){
                return response([
                    'error'=>'Something went wrong. Please try again later!'
                ], 500);
            }
        }
        return response([
            'error'=>'Something went wrong. Please try again later!'
        ], 500);
    }
}
