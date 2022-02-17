<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [
            'count_ads' => Ads::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_ads',
            'title'    => 'Table Ads'
        ];

        if ($request->ajax()) {
            $q_ads = Ads::select('*')->orderByDesc('created_at');

            return DataTables::of($q_ads)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-icon btn-outline-success btn-circle mr-2 edit editUser"><i class=" fi-rr-edit"></i></div>';
                    $btn = $btn.' <div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-icon btn-outline-danger btn-circle mr-2 deleteUser"><i class="fi-rr-trash"></i></div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('layouts.v_template',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Ads::updateOrCreate(['id' => $request->user_id],
            [
                'name' => $request->name,
                'type' => $request->type,
                'image' => $request->image ?? null,
                'video' => $request->video ?? null,
                'status' => $request->status,
            ]);

        return response()->json(['success'=>'Ads saved successfully!']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $User = Ads::find($id);
        return response()->json($User);
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Ads::find($id)->delete();

        return response()->json(['success'=>'Ads deleted!']);
    }
}
