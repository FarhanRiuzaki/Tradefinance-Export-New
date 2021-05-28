<?php

namespace App\Http\Controllers\Transaction;

use App\Classes\Theme\Metronic;
use App\Http\Controllers\Controller;
use App\Models\Master\MasterBranch;
use App\Models\Master\MasterFlag;
use App\Models\MT\Mt700;
use App\Models\MT\Mt707;
use App\Models\MT\Mt710;
use App\Models\Transaction\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdviceApproverController extends Controller
{
    private $page_title         = "Advice L/C";
    private $page_description   = "Advice L/C Aprrover";
    private $permission         = 'advice-approver'; //jamak
    private $route              = 'advice-approvers'; //plural

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
            $data = Transaction::with('flag','mt700','mt710', 'branchs')
             ->whereIn('flag_id', [1,4]) //advice & ammend
            //  ->orderBy('id', 'DESC')
             ->get();

             return DataTables::of($data)
                     ->addIndexColumn()
                     ->editColumn('branch', function ($user) {
                         return $user->branchs->code . ' - ' . $user->branchs->name;
                     })
                     ->editColumn('source', function ($user) {
                         if($user->source == 'doc'){
                             return 'Dokumen';
                         }else{
                             return strtoupper($user->source);
                         }
                     })
                     ->editColumn('created_at', function ($user) {
                         return $user->created_at ? $user->created_at->format('d M Y') : '';
                     })
                     ->editColumn('amount', function ($data) {
                         return number_format($data->amount, 2);
                     })
                     ->editColumn('status', function ($data) {
                        return statusDT($data->flag_id, $data->flag);
                     })
                     ->addColumn('action', function($data){
                        $button = ' <a class="btn btn-icon btn-light btn-sm btn-hover-success" href="'.  route($this->route.'.edit', Crypt::encrypt($data->id)) .'" data-toggle="tooltip"  data-theme="dark" title="Proccess Transaction">
                                '. Metronic::getSVGController("media/svg/icons/Media/Repeat-one.svg", "svg-icon-md svg-icon-success") .'
                        </a>';
                        return $button;
                     })
                     ->rawColumns(['action','status'])
                     ->make(true);
        }

        $route             = $this->route;
        $page_title        = $this->page_title;
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [
            ['page'  => $this->route,'title' =>  $page_title]
        ];

        return view('transaction.advice.approver.index', compact('page_title', 'page_description', 'page_breadcrumbs','route'));
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
        $id         = Crypt::decrypt($id);
        $branch     = MasterBranch::select(DB::raw('concat(code, " - ", name) as code_name'), 'id', 'code')->pluck('code_name', 'code');
        $record     = Transaction::with('mt700', 'mt710','branchs')->find($id);
        // get data MT
        if($record->source == 'MT700'){
            $mt['mt700']        = Mt700::with('mt701')->where('F20', $record->lc_code)->first();

            $show_file["mt700"] = htmlentities(file_get_contents(env('PATH_MT') . '700/Bck/' . $mt['mt700']->filename));
            if($mt['mt700']->mt701){
                $show_file["mt701"] = htmlentities(file_get_contents(env('PATH_MT') . '701/Bck/' . $mt['mt700']->mt701->filename));
            }
            // dd($mt["mt700"]);
        }
        if($record->source == 'MT710'){
            $mt['mt710']        = Mt710::where('F20', $record->lc_code)->first();

            $show_file["mt710"] = file_get_contents(env('PATH_MT') . '710/Bck/' . $mt['mt710']->filename);
            $show_file["mt710"] = htmlentities($show_file["mt710"]);
        }
        if($record->source == 'MT707'){
            $mt['mt707']        = Mt707::with('mt700')->where('F20', $record->lc_code)->first();

            $show_file["mt707"] = file_get_contents(env('PATH_MT') . '707/Bck/' . $mt['mt707']->filename);
            $show_file["mt707"] = htmlentities($show_file["mt707"]);
            if($mt['mt707']->mt700){
                $show_file["mt700"] = htmlentities(file_get_contents(env('PATH_MT') . '700/Bck/' . $mt['mt707']->mt700->filename));
            }
        }

        $route              = $this->route;
        $page_title         = $this->page_title;
        $page_description   = $this->page_description;
        $page_breadcrumbs   = [
            ['page'  => $route,'title' =>  $this->page_title],
            ['page'  => $route.'/edit','title' =>  $this->page_title . ' Approver']
        ];
        $page_buttons       = [
            ['route'=> $route.'.index', 'title' => 'Back','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info'],
            ['type' => 'button',    'route'=> $route.'.index', 'title' => 'Reject','svg' => 'Navigation/Close.svg', 'class' => 'btn-danger btnReject'],
            ['type' => 'button',    'route'=> $route.'.index', 'title' => 'Approve','svg' => 'Navigation/Double-check.svg', 'class' => 'btn-success btnApprove'],
        ];

        return view('transaction.advice.approver.edit',compact('page_title', 'page_description', 'page_breadcrumbs', 'page_buttons','record','branch','route','show_file'));
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
        $id = Crypt::decrypt($id);
        $requestData                    = $request->all();
        $requestData['approve_by']      = Auth::user()->id;
        $requestData['approve_date']    = date('Y-m-d');
        $type = $requestData['flag_id'] == (2 || 5)
                ? 'Approve'
                : 'Reject';

        $flag                       = MasterFlag::find($requestData['flag_id']);
        $requestData['flag_data']   = $flag;

        $save = Transaction::findOrFail($id);
        $save->update($requestData) == true
        ? $return = ['code' => 'success',   'msg' => 'data berhasil di'. $type, 'type' => $type]
        : $return = ['code' => 'error',     'msg' => 'something went wrong!',   'type' => 'error'];

        return response()->json($return);
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
