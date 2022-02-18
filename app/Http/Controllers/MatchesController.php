<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\TokenGenerateController;
use App\Models\Country;
use App\Models\Matches;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class MatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $matches = $this->insert();
        $data = [
            'count_user' => Matches::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_matches',
            'title'    => 'Table Matache',
            'tournament'    => $matches,
        ];

        if ($request->ajax()) {
            $q_tournament = Tournament::select('*')->orderByDesc('created_at');
            return DataTables::of($q_tournament)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $html = "<span class=''>On</span>";
                    if($row['status'] == 0){
                        $html = "<span class=''>Off</span>";
                    }
                    return $html;
                })
                ->addColumn('action', function($row){
                    $btn = '<div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-icon btn-outline-success btn-circle mr-2 edit editUser"><i class=" fi-rr-edit"></i></div>';
//                    $btn = $btn.' <div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-icon btn-outline-danger btn-circle mr-2 deleteUser"><i class="fi-rr-trash"></i></div>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('layouts.v_template',$data);
    }

    public function insert(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $matches = new \App\Http\Controllers\Api\MatchesController();
        $matches = $matches->prepareMatchesData($token);
        dd($matches);
        foreach ($matches as $matche){
            Matches::updateOrInsert($matche,['key'=> $matche['key']]);
        }

        return Matches::all()->toArray();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
