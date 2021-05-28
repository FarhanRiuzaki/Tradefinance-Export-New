@extends('layout.default')
<style>
    .form-group {
        margin-bottom: .5rem !important;
    }
</style>

@section('content')

{{-- include jurnal  --}}
@include('transaction.jurnal.jurnal-advice')

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-custom {{ @$class }}">
            {{-- Header --}}
            <div class="card-header flex-wrap border-0 pt-6 pb-0 ribbon ribbon-clip ribbon-right">
                <div class="card-title">
                    <h3 class="card-label">{{ @$page_description }}
                    </h3>
                </div>
                <div class="card-toolbar">
                    <div class="dropdown dropdown-inline">
                        <a href="#" class="btn btn-light-primary btn-sm font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Generate PDF
                        </a>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            {{-- Navigation --}}
                            <ul class="navi navi-hover">
                                <li class="navi-item">
                                    <a href="#" class="navi-link typeBtn" data-type='notification-letter'>
                                        <i class="navi-icon flaticon2-graph-1"></i>
                                        <span class="navi-text">Notification Letter</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link typeBtn" data-type='nota-debet'>
                                        <i class="navi-icon flaticon2-graph-1"></i>
                                        <span class="navi-text">Nota Debet</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link"data-toggle="modal" data-target="#jurnal-advice"">
                                        <i class="navi-icon flaticon-file-2"></i>
                                        <span class="navi-text">Jurnal</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            {!! Form::model($record, ['method' => 'PATCH','route' => [$route.'.update', $record->id], 'id' => 'MyForm']) !!}
                <div class="card-body pt-4 table-responsive" >
                <div class="row">
                    <div class="col-md-4">
                        {{ Form::inputText('LC CODE',   'lc_code',  null, null, ['disabled']) }}
                        {{ Form::inputText('Amount',    'amount',   null, 'format_number',['disabled']) }}
                        {{ Form::inputText('Currency',  'currency', null, null, ['disabled']) }}
                        <br>
                    </div>
                    <div class="col-md-4">
                        {{ Form::inputTextarea('Beneficiary', 'beneficiary', null,null, ['rows' => '7','disabled']) }}
                    </div>
                    <div class="col-md-4">
                            {{ Form::inputTextarea('Sender', 'sender', null,null, ['rows' => '7','disabled']) }}
                    </div>
                </div>
                <div class="row">
                    @if ($record->source == 'MT700')
                        @include('transaction.advice.maker.mt700.show')
                    @elseif ($record->source == 'MT710')
                        @include('transaction.advice.maker.mt710.show')
                    @else
                        @include('transaction.advice.approver.mt707.edit')
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4><b>>> Data Tambahan</b></h4>
                        <br>
                        {{ Form::inputText('NO CIF', 'cif', null, null, ['disabled']) }}
                        {{ Form::inputText('Cabang', 'branch_code', null, null, ['disabled']) }}
                        {{ Form::inputText('NO Telepon', 'phone', null, null, ['disabled']) }}
                        {{ Form::inputText('NPWP', 'npwp', null, null, ['disabled']) }}
                        {{ Form::inputText('Tujuan L/C', 'lc_purpose', null, null, ['disabled']) }}
                        {{ Form::inputText('Jenis L/C', 'lc_type', null, null, ['disabled']) }}
                        {{ Form::inputText('Jenis Fasilitas', 'facility_type', null, null, ['disabled']) }}
                    </div>
                    <div class="col-md-6">
                        <h4><b>>> No Rekening</b></h4>
                        <br>
                        {{ Form::inputText('Rekening Biaya', 'rek_export', null, null, ['disabled']) }}
                        {{ Form::inputText('Rekening Hasil Export', 'rek_payment', null, null, ['disabled']) }}

                        <br>
                        <h4><b>>> Charges Advice</b></h4>
                        <div class="form-group">
                            <div class="row">
                                {{-- <div class="col-md-3">
                                    {{ Form::label('charges_a', null, ['class' => 'control-label']) }}
                                </div> --}}
                                <div class="col-md-4">
                                    {{ Form::inputText(null, 'charges_currency_a', null, null, ['disabled']) }}
                                </div>
                                <div class="col-md-8">
                                    {{ Form::inputText(null, 'charges_amount_a', null, 'format_number', ['disabled']) }}
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-3">
                                    {{ Form::label('charges_b', null, ['class' => 'control-label']) }}
                                </div>
                                <div class="col-md-3">
                                    {{ Form::inputText(null, 'charges_currency_b', null, null, ['disabled']) }}
                                </div>
                                <div class="col-md-6">
                                    {{ Form::inputText(null, 'charges_amount_b', null, 'format_number', ['disabled']) }}
                                </div>
                            </div> --}}
                        </div>

                        <br>
                        <h4><b>>> Keterangan Notification Letter</b></h4>
                        <br>
                        {{ Form::inputTextarea(null, 'maker_note', null,null, ['rows' => '7', null, null, 'disabled']) }}

                        {{-- yg di hidden --}}
                        <input type="hidden" name="flag_id" id='flag_id'>
                    </div>
                    <div class="col-12">
                        <h4><b>>> Catatan Approver</b></h4>
                        <br>
                        {{ Form::inputTextarea(null, 'approve_note', null,null, ['rows' => '4', 'disabled']) }}

                    </div>
                </div>
            </div>
            {!! Form::close() !!}

            {{-- hidden input  --}}
            <input type="hidden" name='type' id="type">

        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script>
        $('.typeBtn').on('click', function() {
            var type = $(this).data('type');

            if(type == 'notification-letter'){
                window.open('{{ route('apis.notificationLetter', Crypt::encrypt($record->id)) }}', '_blank');
            }
            if(type == 'nota-debet'){
                window.open('{{ route('apis.notaDebet', Crypt::encrypt($record->id)) }}', '_blank');
            }
        });
    </script>
@endpush
