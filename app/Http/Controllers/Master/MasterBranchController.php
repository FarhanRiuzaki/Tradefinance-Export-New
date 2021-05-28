<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class MasterBranchController extends Controller
{
    private $page_title         = "Master Branchs";
    private $page_description   = "Branch Management";

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:branch.index|branch.create|branch.edit|branch.delete', ['only' => ['index','store']]);
        $this->middleware('permission:branch.create',   ['only' => ['create','store']]);
        $this->middleware('permission:branch.edit',     ['only' => ['edit','update']]);
        $this->middleware('permission:branch.delete',   ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()){
            $model = MasterBranch::query();

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('created_at', function($data)
                {
                    return createdAt($data->created_at);
                })
                ->addColumn('action', function($data){
                    return ButtonSED($data, 'branchs', 'branch');
                })
                ->rawColumns(['action','created_at'])
                ->make(true);
        }

        $page_title        = $this->page_title;
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [['page'  => 'branchs','title' =>  'Master Branchs']];
        $page_buttons      = [
            ['route'=> 'branchs.create', 'can' => 'branch.create', 'title' => 'New Record', 'class' => 'btn-primary', 'svg'=> 'Design/Flatten.svg']
        ];
        return view('master.branch.index', compact('page_title', 'page_description', 'page_breadcrumbs','page_buttons'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title        = $this->page_title;
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [['page'  => 'products','title' =>  'Products'], ['page'=>'products/create','title'=>'Product Create']];
        $page_buttons      = [
            ['route'=> 'branchs.index', 'title' => 'Back','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info'],
            ['route'=> 'form', 'id-form' => 'MyForm', 'title' => 'Submit','svg' => 'Communication/Sending.svg', 'class' => 'btn-primary']
        ];

        return view('master.branch.create', compact('page_title','page_description','page_breadcrumbs', 'page_buttons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        MasterBranch::create($request->all());

        return redirect()->route('branchs.index')
        ->with(toaster($this->page_title . ' created successfully', 'success', 'Success'));
    }

    public function destroy($id)
    {
        $id     = Crypt::decrypt($id);
        $delete = MasterBranch::findOrFail($id);
        $delete->delete() == true
            ? $return = ['code' => 'success', 'msg' => 'data deleted successfully']
            : $return = ['code' => 'error', 'msg' => 'something went wrong!'];

        return response()->json($return);
    }
}
