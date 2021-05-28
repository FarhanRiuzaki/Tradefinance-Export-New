<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterDocumentParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class MasterParameterDocumentController extends Controller
{
    private $page_title         = "Master Parameter Documents";
    private $page_description   = "Parameter Documents Management";
    private $permission         = 'parameter-document'; //jamak
    private $route              = 'parameter-documents'; //plural

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:'.$this->permission.'.index|'.$this->permission.'.create|'.$this->permission.'.edit|'.$this->permission.'.delete', ['only' => ['index','store']]);
        $this->middleware('permission:'.$this->permission.'.create',   ['only' => ['create','store']]);
        $this->middleware('permission:'.$this->permission.'.edit',     ['only' => ['edit','update']]);
        $this->middleware('permission:'.$this->permission.'.delete',   ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()){
            $model = MasterDocumentParameter::query();

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('created_at', function($data)
                {
                    return createdAt($data->created_at);
                })
                ->editColumn('status', function ($user) {
                    return $user->status == true
                        ? "<span class='btn btn-icon btn-success btn-sm'><i class='fa fa-check'></i></span>"
                        :"<span class='btn btn-icon btn-danger btn-sm'><i class='fa fa-times'></i></span>";
                })
                ->addColumn('action', function($data){
                    return ButtonSED($data, $this->route, $this->permission, false);
                })
                ->rawColumns(['action','created_at','status'])
                ->make(true);
        }

        $route             = $this->route;
        $page_title        = $this->page_title;
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [['page'  => $this->route,'title' =>  $page_title]];

        return view('master.parameter-document.index', compact('page_title', 'page_description', 'page_breadcrumbs','route'));
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
        $requestData = $request->all();
        if(isset($requestData['status'])){
            $requestData['status'] = true;
        }else{
            $requestData['status'] = false;
        }
        // dd($requestData);
        MasterDocumentParameter::create($requestData);

        return redirect()->route($this->route.'.index')
        ->with(toaster($this->page_title . ' created successfully', 'success', 'Success'));
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
        $id                 = Crypt::decrypt($id);
        $edit               = MasterDocumentParameter::find($id);

        $route              = $this->route;
        $page_title         = $this->page_title;
        $page_description   = $this->page_description;
        $page_breadcrumbs   = [['page'  => $this->route,'title' =>  $page_title]];
        $page_buttons       = [
            ['route'=> $this->route .'.index', 'title' => 'Reset','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info'],
        ];

        return view('master.parameter-document.index', compact('page_title', 'page_description', 'page_breadcrumbs', 'page_buttons', 'route','edit'));
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
        $id             = Crypt::decrypt($id);
        $requestData    = $request->all();
        if(isset($requestData['status'])){
            $requestData['status'] = true;
        }else{
            $requestData['status'] = false;
        }
        $edit   = MasterDocumentParameter::find($id);
        $edit->update($requestData);

        return redirect()->route($this->route.'.index')
        ->with(toaster($this->page_title .' updated successfully', 'success', 'Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id     = Crypt::decrypt($id);
        $delete = MasterDocumentParameter::findOrFail($id);
        $delete->delete() == true
            ? $return = ['code' => 'success', 'msg' => 'data deleted successfully']
            : $return = ['code' => 'error', 'msg' => 'something went wrong!'];

        return response()->json($return);
    }
}
