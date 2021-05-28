<?php

namespace App\Http\Controllers\Transaction;

use App\Classes\Theme\Metronic;
use App\Http\Controllers\Controller;
use App\Models\Master\MasterBranch;
use App\Models\Master\MasterCurrency;
use App\Models\Master\MasterFlag;
use App\Models\MT\Mt700;
use App\Models\MT\Mt707;
use App\Models\MT\Mt710;
use App\Models\Session;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionMt700;
use App\Models\Transaction\TransactionMt707;
use App\Models\Transaction\TransactionMt710;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class AdviceMakerController extends Controller
{
    private $page_title         = "Advice L/C";
    private $page_description   = "Advice L/C Management";
    private $permission         = 'advice-maker'; //jamak
    private $route              = 'advice-makers'; //plural

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
        $route             = $this->route;
        $page_title        = $this->page_title;
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [['page'  => $this->route,'title' =>  $page_title]];
        $page_buttons      = [
            ['route'=> $route.'.indexSummary', 'title' => 'Summary LC','svg' => 'Communication/Clipboard-list.svg', 'class' => 'btn-warning'],
        ];

        return view('transaction.advice.maker.index', compact('page_title', 'page_description', 'page_breadcrumbs','route','page_buttons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Proses encrypt
        $data       = Crypt::decrypt($request->data);
        $explode    = explode('|', $data);
        // proses data
        $type       = $explode[0]; //Tipe Trx
        $lc_code    = $explode[1]; //Kode LC

        // get master data
        $branch     = MasterBranch::select(DB::raw('concat(code, " - ", name) as code_name'), 'id', 'code')->pluck('code_name', 'code');
        $currency   = MasterCurrency::pluck('code', 'code');

        // get data MT
        if($type == 'MT700'){
            $mt['mt700']        = Mt700::with('mt701')->where('F20', $lc_code)->first();

            $show_file["mt700"] = htmlentities(file_get_contents(env('PATH_MT') . '700/Bck/' . $mt['mt700']->filename));
            if($mt['mt700']->mt701){
                $show_file["mt701"] = htmlentities(file_get_contents(env('PATH_MT') . '701/Bck/' . $mt['mt700']->mt701->filename));
            }
        }

        if($type == 'MT710'){
            $mt['mt710']        = Mt710::where('F20', $lc_code)->first();

            $show_file["mt710"] = file_get_contents(env('PATH_MT') . '710/Bck/' . $mt['mt710']->filename);
            $show_file["mt710"] = htmlentities($show_file["mt710"]);
        }

        if($type == 'MT707'){
            $mt['mt707']        = Mt700::with('mt707')->where('F20', $lc_code)->first();

            $show_file["mt707"] = file_get_contents(env('PATH_MT') . '707/Bck/' . $mt['mt707']->mt707->filename);
            $show_file["mt707"] = htmlentities($show_file["mt707"]);
            if($mt['mt707']){
                $show_file["mt700"] = htmlentities(file_get_contents(env('PATH_MT') . '700/Bck/' . $mt['mt707']->filename));
            }
        }

        $route             = $this->route;
        $page_title        = $this->page_title;
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [
            ['page'  => $this->route,'title' =>  $page_title],
            ['page'  => $this->route . '/create','title' =>  'Create ' . $page_title],

        ];
        $page_buttons      = [
            ['route'=> $route.'.index', 'title' => 'Back','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info'],
            ['route'=> 'form', 'id-form' => 'MyForm', 'title' => 'Submit','svg' => 'Communication/Sending.svg', 'class' => 'btn-primary']
        ];
        // debug($mt);
        return view('transaction.advice.maker.create', compact('page_title', 'page_description', 'page_breadcrumbs','route','page_buttons','branch','currency', 'mt','show_file'));
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
        // kode otomatis
        $prefix     = 'ADV';
        $code       = Transaction::select(DB::raw('IFNULL(MAX(SUBSTR(code, -11,4)), 0) + 1 as code'))->orderBy('id', 'DESC')->first();

        if($code->code < 10){
            $count = '0000' . $code->code;
        }elseif ($code->code < 100) {
            $count = '000' . $code->code;
        }elseif ($code->code < 1000) {
            $count = '00' . $code->code;
        }elseif ($code->code < 10000) {
            $count = '0' . $code->code;
        }

        $code   = $prefix . '/' . $count . '/' . $requestData['branch_code'] . '/' . date('Y');
        $requestData['code']    = $code;

        // Custom request
        $requestData['source']      == 'MT707'
            ? $requestData['flag_id']   = '4'
            : $requestData['flag_id']   = '1';

        // $requestData['flag_id']         = '1';  //Advice submittes
        $requestData['counter_revisi']  = 0;    //Percobaan revisi default

        // get flag data
        $flag                       = MasterFlag::find($requestData['flag_id']);
        $requestData['flag_data']   = $flag;  //Advice submittes

        if($request->transactions_mt700){   //TODO cek isi di dalam kondisi, sesuaikan dengan type MT
            // $requestData['amount']              = $requestData['transactions_mt700']['F32B_AMT'];
            // $requestData['currency']            = $requestData['transactions_mt700']['F32B_CUR'];
            // $requestData['beneficiary']         = $requestData['transactions_mt700']['F59'];
            if($requestData['transactions_mt700']['F39A']){
                $requestData['amount_limit']  = substr($requestData['transactions_mt700']['F39A'], strpos($requestData['transactions_mt700']['F39A'], "/") + 1);
                $requestData['amount_limit']  = (integer) $requestData['amount_limit'];
            }else{
                $requestData['amount_limit']  = 1;
            }
        }

        if($request->transactions_mt710){   //TODO cek isi di dalam kondisi, sesuaikan dengan type MT
            // $requestData['amount']              = $requestData['transactions_mt710']['F32B_AMT'];
            // $requestData['currency']            = $requestData['transactions_mt710']['F32B_CUR'];
            // $requestData['beneficiary']         = $requestData['transactions_mt710']['F59'];
            if($requestData['transactions_mt710']['F39A']){
                $requestData['amount_limit']  = substr($requestData['transactions_mt710']['F39A'], strpos($requestData['transactions_mt710']['F39A'], "/") + 1);
                $requestData['amount_limit']  = (integer) $requestData['amount_limit'];
            }else{
                $requestData['amount_limit']  = 1;
            }
        }

        if($request->transactions_mt707){   //TODO cek isi di dalam kondisi, sesuaikan dengan type MT
            // $requestData['amount']              = $requestData['transactions_mt707']['F32B_AMT'];
            // $requestData['currency']            = $requestData['transactions_mt707']['F32B_CUR'];
            // $requestData['beneficiary']         = $requestData['transactions_mt707']['F59'];
            if($requestData['transactions_mt707']['F39A']){
                $requestData['amount_limit']  = substr($requestData['transactions_mt707']['F39A'], strpos($requestData['transactions_mt707']['F39A'], "/") + 1);
                $requestData['amount_limit']  = (integer) $requestData['amount_limit'];
            }else{
                $requestData['amount_limit']  = 1;
            }
        }

        $requestData['amount']              = convertNumber($requestData['amount']);
        $requestData['charges_amount_a']    = convertNumber($requestData['charges_amount_a']);

        // dd($requestData);
        DB::beginTransaction();
        try {
            // save transaksi
            $save = Transaction::create($requestData);
            // cek jika mt700
            if($request->transactions_mt700){
                // menyimpan relasi one to one ke mt700
                $mt700 = new TransactionMt700($request->transactions_mt700);
                $save->mt700()->save($mt700);
            }

            // cek jika mt710
            if($request->transactions_mt710){
                // menyimpan relasi one to one ke mt700
                $save_record = new TransactionMt710($request->transactions_mt710);
                $save->mt710()->save($save_record);
            }

            // cek jika mt707
            if($request->transactions_mt707){
                // menyimpan relasi one to one ke mt700
                $save_record = new TransactionMt707($request->transactions_mt707);
                $save->mt710()->save($save_record);
            }

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            $save = false;
            return redirect()->back()
            ->with(toaster('ERROR: ' . $e->errorInfo[2], 'error', 'Oops..'));
            // something went wrong
        }
        // dd($save, $e->errorInfo[2]);

        // cek jika dokumen
        // if($request->transactions_document){
        //     foreach ($request->transactions_document as $key => $val) {
        //         $name       = $val['image']->getClientOriginalName();
        //         $file       = $val['image'];
        //         $filename   = time() . Str::slug($name) . '.' . $file->getClientOriginalExtension();
        //         $file->storeAs('public/transactions_documents', $filename);

        //         // SAVE KE DB
        //         $product = TransactionsDocument::create([
        //             'transactions_id'       => $save->id,
        //             'image'                 => $filename,
        //         ]);
        //     }
        // }
        if($save){
            $msg = [
                'id'    => $save->id,
                'type'  => 'PDF'
            ];
        }else{
            $msg = [
                'id'    => false,
                'type'  => 'PDF'
            ];
        }
        $request->session()->put('msg', $msg);

        return redirect()->route('advice-makers.index')
        ->with(toaster( $this->page_title . ' created successfully', 'success', 'Success'));
    }

    public function indexSummary(Request $req)
    {
        if($req->ajax()){
            $data = Transaction::with('flag','mt700','mt710', 'branchs')
            ->whereIn('flag_id', [1,3]) //submit & reject
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
                    if($data->flag_id == 3){
                        $button = ' <a class="btn btn-icon btn-light btn-sm btn-hover-warning" href="'.  route($this->route.'.show', Crypt::encrypt($data->id)) .'" data-toggle="tooltip"  data-theme="dark" title="Show Transaction">
                            '. Metronic::getSVGController("media/svg/icons/General/Settings-1.svg", "svg-icon-md svg-icon-warning") .'
                        </a>';
                        $button .= ' <a class="btn btn-icon btn-light btn-sm btn-hover-success" href="'.  route($this->route.'.editSummary', Crypt::encrypt($data->id)) .'" data-toggle="tooltip"  data-theme="dark" title="Proccess Transaction">
                                '. Metronic::getSVGController("media/svg/icons/Media/Repeat-one.svg", "svg-icon-md svg-icon-success") .'
                        </a>';

                    }else{
                        $button = ' <a class="btn btn-icon btn-light btn-sm btn-hover-warning" href="'.  route($this->route.'.show', Crypt::encrypt($data->id)) .'" data-toggle="tooltip"  data-theme="dark" title="Show Transaction">
                            '. Metronic::getSVGController("media/svg/icons/General/Settings-1.svg", "svg-icon-md svg-icon-warning") .'
                        </a>';
                    }
                return $button;
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }

        $route             = $this->route;
        $page_title        = $this->page_title;
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [
            ['page'  => $this->route,'title' =>  $page_title],
            ['page'  => $route.'/index-summary','title' =>  $this->page_title . ' Summary']
        ];
        $page_buttons      = [
            ['route'=> $this->route .'.index', 'title' => 'Back','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info'],
        ];

        return view('transaction.advice.maker.index-summary', compact('page_title', 'page_description', 'page_breadcrumbs','route','page_buttons'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        }
        if($record->source == 'MT710'){
            $mt['mt710']        = Mt710::where('F20', $record->lc_code)->first();

            $show_file["mt710"] = file_get_contents(env('PATH_MT') . '710/Bck/' . $mt['mt710']->filename);
            $show_file["mt710"] = htmlentities($show_file["mt710"]);
        }
        if($record->source == 'MT707'){
            $mt['mt707']        = Mt707::where('F20', $record->lc_code)->first();

            $show_file["mt707"] = file_get_contents(env('PATH_MT') . '707/Bck/' . $mt['mt707']->filename);
            $show_file["mt707"] = htmlentities($show_file["mt707"]);
        }

        $route              = $this->route;
        $page_title         = $this->page_title;
        $page_description   = $this->page_description;
        $page_breadcrumbs  = [
            ['page'  => $this->route,'title' =>  $page_title],
            ['page'  => $route.'/index-summary','title' =>  $this->page_title . ' Summary'],
            ['page'  => '#','title' =>  $this->page_title . ' Show'],
        ];
        $page_buttons      = [
            ['route'=> $this->route .'.indexSummary', 'title' => 'Back','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info'],
        ];

        return view('transaction.advice.maker.show-summary',compact('page_title', 'page_description', 'page_breadcrumbs','page_buttons','record','branch','route','show_file'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSummary($id)
    {
        $id         = Crypt::decrypt($id);
        $branch     = MasterBranch::select(DB::raw('concat(code, " - ", name) as code_name'), 'id', 'code')->pluck('code_name', 'code');
        $record     = Transaction::with('mt700', 'mt710','branchs')->find($id);
        $currency   = MasterCurrency::pluck('code', 'code');

        // get data MT
        if($record->source == 'MT700'){
            $mt['mt700']        = Mt700::with('mt701')->where('F20', $record->lc_code)->first();

            $show_file["mt700"] = htmlentities(file_get_contents(env('PATH_MT') . '700/Bck/' . $mt['mt700']->filename));
            if($mt['mt700']->mt701){
                $show_file["mt701"] = htmlentities(file_get_contents(env('PATH_MT') . '701/Bck/' . $mt['mt700']->mt701->filename));
            }
        }
        if($record->source == 'MT710'){
            $mt['mt710']        = Mt710::where('F20', $record->lc_code)->first();

            $show_file["mt710"] = file_get_contents(env('PATH_MT') . '710/Bck/' . $mt['mt710']->filename);
            $show_file["mt710"] = htmlentities($show_file["mt710"]);
        }
        if($record->source == 'MT707'){
            $mt['mt707']        = Mt707::where('F20', $record->lc_code)->first();

            $show_file["mt707"] = file_get_contents(env('PATH_MT') . '707/Bck/' . $mt['mt707']->filename);
            $show_file["mt707"] = htmlentities($show_file["mt707"]);
        }

        $route              = $this->route;
        $page_title         = $this->page_title;
        $page_description   = $this->page_description;
        $page_breadcrumbs  = [
            ['page'  => $this->route,'title' =>  $page_title],
            ['page'  => $route.'/index-summary','title' =>  $this->page_title . ' Summary'],
            ['page'  => '#','title' =>  $this->page_title . ' Proccess'],
        ];
        $page_buttons      = [
            ['route'=> $this->route .'.indexSummary', 'title' => 'Back','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info'],
            ['type' => 'button', 'title' => 'Update','svg' => 'Communication/Sending.svg', 'class' => 'btn-warning btnUpdate'],
        ];

        return view('transaction.advice.maker.edit',compact('page_title', 'page_description', 'page_breadcrumbs','page_buttons','record','branch','route','show_file','currency'));

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
        $edit           = Transaction::find($id);
        $requestData['counter_revisi']  = $edit->counter_revisi+1;
        $requestData['amount']          = convertNumber($edit->amount);
        $edit->update($requestData) == true
        ? $return = ['code' => 'success', 'msg' => 'data updated successfully']
        : $return = ['code' => 'error', 'msg' => 'something went wrong!'];

        return response()->json($return);


        // return redirect()->route('advice-makers.index')
        // ->with(toaster( $this->page_title . ' update successfully', 'success', 'Success'));
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

    public function resource(Request $req)
    {
        if($req->ajax()){

            if(!isset($req->type)){ //defaut
                $transaction = Transaction::where('source', 'MT700')->pluck('lc_code')->toArray();
                $MT707       = Mt707::pluck('F20')->toArray();
                $model = Mt700::with('mt701')->whereNotIn('F20',array_merge($transaction, $MT707))->get();
                return $this->datatableMt700($model, 'MT700');
            }else{
                if($req->type == 'MT700'){  //MT700
                    $transaction = Transaction::where('source', 'MT700')->pluck('lc_code')->toArray();
                    $MT707       = Mt707::pluck('F20')->toArray();
                    $model = Mt700::with('mt701')->whereNotIn('F20',array_merge($transaction,$MT707))->get();
                    return $this->datatableMt700($model, $req->type);
                }
                if($req->type == 'MT710'){  //MT710
                    $transaction = Transaction::where('source', 'MT710')->pluck('lc_code')->toArray();
                    $model = Mt710::whereNotIn('F20',$transaction)->get();
                    return $this->datatableMt710($model, $req->type);
                }
                if($req->type == "MT707"){ //MT707
                    $model = Mt707::has('mt700')->get();
                    return $this->datatableMt707($model,$req->type);
                }
            }
        }
    }

    public function datatableMt700($model, $type)
    {
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('no_lc',        function($data){ return $data->F20; })
            ->addColumn('beneficiary',  function($data){ return $data->F59; })
            ->addColumn('currency',     function($data){ return $data->F32B_CUR; })
            ->addColumn('amount',       function($data){ return number_format($data->F32B_AMT, 2); })
            ->addColumn('created_at',   function($data)
            {
                return createdAt($data->created_date);
            })
            ->addColumn('action', function($data) use($type){
                // return ButtonSED($data, $this->route, $this->permission, false);
                $button = ' <a class="btn btn-icon btn-light btn-sm btn-hover-success" href="'.  route($this->route.'.create') .'?data='.Crypt::encrypt($type .'|' . $data->F20).'" data-toggle="tooltip"  data-theme="dark" title="Create Transaction">
                        '. Metronic::getSVGController("media/svg/icons/Files/Cloud-upload.svg", "svg-icon-md svg-icon-success") .'
                </a>';
                return $button;
            })
            ->rawColumns(['action','created_at','status'])
            ->make(true);
    }

    public function datatableMt710($model, $type)
    {
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('no_lc',        function($data){ return $data->F20; })
            ->addColumn('beneficiary',  function($data){ return $data->F59; })
            ->addColumn('currency',     function($data){ return $data->F32B_CUR; })
            ->addColumn('amount',       function($data){ return number_format($data->F32B_AMT, 2); })
            ->addColumn('created_at',   function($data)
            {
                return createdAt($data->created_date);
            })
            ->addColumn('action', function($data) use($type){
                // return ButtonSED($data, $this->route, $this->permission, false);
                $button = ' <a class="btn btn-icon btn-light btn-sm btn-hover-success" href="'.  route($this->route.'.create') .'?data='.Crypt::encrypt($type . '|' . $data->F20).'" data-toggle="tooltip"  data-theme="dark" title="Create Transaction">
                        '. Metronic::getSVGController("media/svg/icons/Files/Cloud-upload.svg", "svg-icon-md svg-icon-success") .'
                </a>';
                return $button;
            })
            ->rawColumns(['action','created_at','status'])
            ->make(true);
    }

    public function datatableMt707($model, $type)
    {
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('no_lc',        function($data){ return $data->mt700->F20; })
            ->addColumn('beneficiary',  function($data){ return $data->mt700->F59; })
            ->addColumn('currency',     function($data){ return $data->mt700->F32B_CUR; })
            ->addColumn('amount',       function($data){ return number_format($data->mt700->F32B_AMT, 2); })
            ->addColumn('created_at',   function($data)
            {
                return createdAt($data->created_date);
            })
            ->addColumn('action', function($data) use($type){
                // return ButtonSED($data, $this->route, $this->permission, false);
                $button = ' <a class="btn btn-icon btn-light btn-sm btn-hover-success" href="'.  route($this->route.'.create') .'?data='.Crypt::encrypt($type .'|' . $data->F20).'" data-toggle="tooltip"  data-theme="dark" title="Create Transaction">
                        '. Metronic::getSVGController("media/svg/icons/Files/Cloud-upload.svg", "svg-icon-md svg-icon-success") .'
                </a>';
                return $button;
            })
            ->rawColumns(['action','created_at','status'])
            ->make(true);
    }

}
