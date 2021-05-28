@extends('layout.default')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-custom {{ @$class }}">
            {{-- Header --}}
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Add SOR
                    </h3>
                </div>
            </div>
            {{-- Body --}}
            <div class="card-body pt-4" >
                {!! Form::model($record, ['method' => 'PATCH','route' => [$route.'.update', $record->id], 'id' => 'MyForm']) !!}

                    <div class="row">
                        <div class="col-md-6">
                            {{ Form::inputText('NO SOR', 'code', null, null,['readonly']) }}
                            <div class="row">
                                <div class="col-md-4">
                                    {{ Form::inputText('Currency', 'currency', null, null,['readonly']) }}
                                </div>
                                <div class="col-md-8">
                                    {{ Form::inputText('Amount', 'amount', null , 'format_number',['placeholder' => 'amount..', 'readonly']) }}
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="col-md-12">
                            <h4><b> Daftar Dokumen</b></h4>
                            <br>
                            <table class="table table-bordered tableFile">
                                <thead>
                                    <tr class="text-center">
                                        <th width='5px'>No</th>
                                        <th width='25%'>Dokumen</th>
                                        <th>Keterangan</th>
                                        <th width='30%'>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($record->document as $item)
                                        <tr class="text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->parameter->code . ' - ' .$item->parameter->name }}</td>
                                            <td>{{ $item->note }}</td>
                                            <td align="left">
                                                <a href="{{ asset('storage/transaction_sor_documents/' . $item->file) }}" target="_BLANK">{{ $item->file }}</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="3">data tidka tersedia..</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
