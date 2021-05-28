<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterFlag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterFlagController extends Controller
{
    private $page_title         = "Master Flags";
    private $page_description   = "Flag Management";

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:flag.index|flag.create|flag.edit|flag.delete', ['only' => ['index','store']]);
        $this->middleware('permission:flag.create',    ['only' => ['create','store']]);
        $this->middleware('permission:flag.edit',     ['only' => ['edit','update']]);
        $this->middleware('permission:flag.delete',   ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()){
            $model = MasterFlag::query();

            return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('created_at', function($data)
            {
                return createdAt($data->created_at);
            })
            ->rawColumns(['action','created_at'])
            ->make(true);
        }

        $page_title        = $this->page_title;
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [['page'  => 'flags','title' =>  'Master Flags']];

        return view('master.flag.index', compact('page_title', 'page_description', 'page_breadcrumbs'));
    }

    public function store(Request $request)
    {
        $requestData    = $request->all();
        MasterFlag::Create($requestData);

        return redirect()->route('flags.index')
        ->with(toaster($this->page_title . ' created successfully', 'success', 'Success'));
    }
}
