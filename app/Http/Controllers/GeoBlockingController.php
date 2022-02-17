<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\TokenGenerateController;
use App\Models\Country;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GeoBlockingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $country =  $this->insertCountry();
        $data = [
            'count_user' => Country::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_geo-blocking',
            'title'    => 'Table GEO Blocking',
            'country' => $country
        ];

        if ($request->ajax()) {
            $q_country = Country::select('*')->orderByDesc('created_at');
            return DataTables::of($q_country)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $html = "<span class=''>Active</span>";
                    if($row['status'] == 0){
                        $html = "<span class=''>Blocked</span>";
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

    public function insertCountry(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $country = new CountryController();
        $countries = $country->getCountry($token);
        foreach ($countries as $country){
            Country::updateOrInsert($country,['short_code'=> $country['short_code']]);
        }

        return Country::all()->toArray();

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

        Country::updateOrCreate(['id' => $request->id],
            [
                'status' => $request->status,
            ]);

        return response()->json(['success'=>'country saved successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Country = Country::find($id);
        return response()->json($Country);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Country = Country::find($id);
        return response()->json($Country);
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
