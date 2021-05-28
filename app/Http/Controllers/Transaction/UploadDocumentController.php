<?php

namespace App\Http\Controllers\Transaction;

use App\Classes\Theme\Metronic;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionSor;
use App\Models\Transaction\TransactionSorDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UploadDocumentController extends Controller
{
    private $page_title         = "Upload Document";
    private $page_description   = "Upload Document Management";
    private $permission         = 'upload-document'; //jamak
    private $route              = 'upload-documents'; //plural

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
            //  ->whereIn('flag_id', [2,3,5,6]) //advice & ammend
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
                        $button = ' <a class="btn btn-icon btn-light btn-sm btn-hover-success" href="'.  route($this->route.'.indexSor', Crypt::encrypt($data->id)) .'" data-toggle="tooltip"  data-theme="dark" title="Upload Document">
                                '. Metronic::getSVGController("media/svg/icons/Files/Cloud-upload.svg", "svg-icon-md svg-icon-success") .'
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

        return view('transaction.upload-document.index', compact('page_title', 'page_description', 'page_breadcrumbs','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $id = decrypt($req->data);
    // GET SUM AMOUNT
        $amount_sor     = TransactionSor::where('transactions_id', $id)->sum('amount');;
    // CODE SOR
        // ambil data transaksi
        $transaction    = Transaction::find($id);
        // generate kode otomatis
        $parent         = TransactionSor::orderBy('id', 'DESC')->first();

        $prefix = 'SOR/';
        $year   = date('Y');
        if($parent == null){
            $code = $prefix . '0001/' . $transaction->branch_code . '/' . $year;
        }else{
            $get_last_code  = $parent->code;
            $get_last_code  = (int) substr($get_last_code, 4, 4);
            $get_last_code  = $get_last_code + 1;
            if($get_last_code < 10){
                $last_code = '000' . $get_last_code;
            }elseif($get_last_code < 100){
                $last_code = '00' . $get_last_code;
            }elseif($get_last_code < 1000){
                $last_code = '0' . $get_last_code;
            }elseif($get_last_code < 10000){
                $last_code = '' . $get_last_code;
            }

            $code = $prefix . $last_code .'/' . $transaction->branch_code . '/' . $year;
        }

        $route             = $this->route;
        $page_title        = 'Add SOR';
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [
            ['page'  => $this->route ,'title' =>  'Upload Document'],
            ['page'  => $this->route. '/sor/'. encrypt($id) ,'title' =>  'List SOR'],
            ['page'  => $this->route ,'title' =>  $page_title]
        ];
        $page_buttons       = [
            ['route'=> $route.'.indexSor', 'title' => 'Back','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info', 'request' => encrypt($id)],
            ['type' => 'button',  'route'=> $route.'.store', 'title' => 'Submit','svg' => 'Navigation/Double-check.svg', 'class' => 'btn-primary btnSubmit'],
        ];

        return view('transaction.upload-document.sor.create', compact('page_title', 'page_description', 'page_breadcrumbs','route','page_buttons','amount_sor','code','transaction'));
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
        // SET REQUEST
        $requestData['transactions_id'] = decrypt($requestData['transactions_id']);
        $requestData['amount']          = convertNumber($requestData['amount']);
        $requestData['flag_id']         = '7';

        DB::beginTransaction();
        try {
            $save = TransactionSor::create($requestData);
            foreach ($request->transaction_sors_document as $key => $val) {
                $name       = $val['document']->getClientOriginalName();
                $file       = $val['document'];
                $filename   = time() . Str::slug($name) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/transaction_sor_documents', $filename);

                // SAVE KE DB
                $pro = TransactionSorDocument::create([
                    'transaction_sors_id'       => $save->id,
                    'code'                      => $val['code'],
                    'note'                      => $val['note'],
                    'file'                      => $filename,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
            ->with(toaster('ERROR: ' . $e->errorInfo[2], 'error', 'Oops..'));
            // something went wrong
        }
        // dd($requestData);

        return redirect()->route('upload-documents.index')
        ->with(toaster( $this->page_title . ' created successfully', 'success', 'Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id     = decrypt($id);
        $record = TransactionSor::with('document')->find($id);

        $route             = $this->route;
        $page_title        = 'Show SOR';
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [
            ['page'  => $this->route ,'title' =>  'Upload Document'],
            ['page'  => $this->route. '/sor/'. encrypt($id) ,'title' =>  'List SOR'],
            ['page'  => $this->route ,'title' =>  $page_title]
        ];
        $page_buttons       = [
            ['route'=> $route.'.indexSor', 'title' => 'Back','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info', 'request' => encrypt($id)],
        ];

        return view('transaction.upload-document.sor.show', compact('page_title', 'page_description', 'page_breadcrumbs','route','page_buttons','record'));
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


    // SOR
    public function indexSor($id, Request $req)
    {
        $id = decrypt($id);
        if($req->ajax())
        {
        $data = TransactionSor::with('transaction', 'document','flag')->where('transactions_id','=', $id)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('amount', function($data)
            {
                return number_format($data->amount, 2);
            })
            ->addColumn('status_lc', function($data)
            {
                return '<label class="badge badge-warning">-</label>';
            })
            ->addColumn('status_sor', function($data)
            {
                return '<label class="badge badge-warning">-</label>';
            })
            ->addColumn('action', function($data){
                $button ='<a class="btn btn-icon btn-light btn-sm btn-hover-warning" href="'.  route($this->route.'.show',Crypt::encrypt($data->id)) .'" data-toggle="tooltip"  data-theme="dark" title="Show">
                '. Metronic::getSVGController("media/svg/icons/General/Settings-1.svg", "svg-icon-md svg-icon-warning") .'
                </a>';
                return $button;
            })
            ->rawColumns(['action','status_sor', 'status_lc'])
            ->make(true);
        }

        $route             = $this->route;
        $page_title        = 'List SOR';
        $page_description  = $this->page_description;
        $page_breadcrumbs  = [
            ['page'  => $this->route,'title' =>  'Upload Document'],
            ['page'  => $this->route ,'title' =>  $page_title]
        ];
        $page_buttons       = [
            ['route'=> $route.'.index', 'title' => 'Back','svg' => 'Navigation/Angle-double-left.svg', 'class' => 'btn-info'],
            ['route'=> $route.'.create', 'title' => 'Add SOR','svg' => 'Code/Plus.svg', 'class' => 'btn-primary', 'request' => 'data='.encrypt($id)],
        ];

        return view('transaction.upload-document.sor.index', compact('page_title', 'page_description', 'page_breadcrumbs','route','page_buttons'));
    }
}
