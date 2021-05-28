@extends('layout.default')

@section('content')
<div class="row">
    <div class="col-xs-8 col-sm-8 col-md-8 offset-2">
        <div class="card card-custom {{ @$class }}">
            {{-- Header --}}
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ @$page_description }}
                    </h3>
                </div>
            </div>
            {{-- Body --}}
            <div class="card-body pt-4" >
                {!! Form::open(array('route' => 'branchs.store','method'=>'POST','id' => 'MyForm', 'enctype' => 'multipart/form-data')) !!}

                {{ Form::inputText('ID Cabang*', 'code', null, null,['placeholder' => '000, 808', 'required']) }}
                {{ Form::inputText('Kode Cabang*', 'branch', null, null,['placeholder' => 'masukan kode cabang', 'required']) }}
                {{ Form::inputText('Nama Cabang*', 'name', null, null,['placeholder' => 'masukan nama cabang', 'required']) }}
                {{ Form::inputText('Tax', 'tax',null, null,['placeholder' => 'masukan tax']) }}
                {{ Form::inputText('Fax', 'fax',null, null,['placeholder' => 'masukan fax']) }}
                {{ Form::inputText('Telepon*', 'phone',null, null,['placeholder' => 'masukan nomer telepon', 'required']) }}
                {{ Form::inputTextarea('Alamat*', 'address', null, null,['placeholder' => 'masukan alamat', 'required']) }}

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
