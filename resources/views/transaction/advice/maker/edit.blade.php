@extends('layout.default')
<style>
    .form-group {
        margin-bottom: .5rem !important;
    }
</style>
{{-- PROSES ENCRYPT DATA  --}}
@php
    $type       = $record->source; // Tipe MT
    $lc_code    = $record->lc_code; // No LC
    // dd($data);
@endphp
@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-custom {{ @$class }}">
            {{-- Header --}}
            <div class="card-header flex-wrap border-0 pt-6 pb-0 ribbon ribbon-clip ribbon-right">
                <div class="ribbon-target" style="top: 15px; height: 35px;">
                    <span class="ribbon-inner bg-primary"></span>{{ $type }}
                </div>
                <div class="card-title">
                    <h3 class="card-label">{{ @$page_description }}
                    </h3>
                </div>
            </div>

            {{-- Body --}}
            {!! Form::model($record, ['method' => 'PATCH','route' => [$route.'.update', Crypt::encrypt($record->id)], 'id' => 'MyForm']) !!}
            <div class="card-body pt-4 table-responsive" >
                <div class="row">
                    <div class="col-md-4">
                        @if ($lc_code)
                            {{ Form::inputText('LC CODE',   'lc_code',  null, null, ['readonly']) }}

                            @if ($type)
                                {{ Form::inputText('Amount',    'amount',   null, 'format_number',['placeholder' => 'amount..', $record->amount != '' ? 'readonly' : 'required']) }}
                                {{ Form::inputText('Currency',  'currency', null, null, [$record->currency != '' ? 'readonly' : 'required']) }}
                            @else
                                {{ Form::inputText('Amount',    'amount',   null, 'format_number',['placeholder' => 'amount..', 'required']) }}
                                {{ Form::inputSelect('Currency', 'currency', $currency) }}
                            @endif
                        @else
                            {{ Form::inputText('LC CODE', 'lc_code', null, null, ['required']) }}
                            {{ Form::inputText('Amount', 'amount', null, 'format_number',['placeholder' => 'amount..', 'required']) }}
                            {{-- {{ Form::inputText('Batas Atas Amount (%)', 'amount_upper_limit', null, 'format_number',['placeholder' => 'batas atas', 'required']) }} --}}
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="Batas Atas Amount (%)" class="control-label">Batas Atas Amount (%)</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                        <input type="text" class="form-control {{ isValid($errors->has('amount_upper_limit')) }}" aria-label="Dollar amount (with dot and two decimal places)" value='0'>
                                            <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                            </div>
                                            <p class="invalid-feedback">{{ $errors->first('amount_upper_limit') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::inputSelect('Currency', 'currency', $currency) }}
                            {{ Form::inputTextarea('Beneficiary', 'beneficiary', null,null, ['rows' => '7','required']) }}
                        @endif
                        <br>
                    </div>
                    <div class="col-md-4">
                        {{ Form::inputTextarea('Beneficiary', 'beneficiary', null,null, ['rows' => '7','required']) }}
                    </div>
                    <div class="col-md-4">
                            {{ Form::inputTextarea('Sender', 'sender', null,null, ['rows' => '7','required']) }}
                    </div>
                </div>
                <div class="row">
                    @if ($type == 'MT700')
                        @include('transaction.advice.maker.mt700.edit')
                    @elseif ($type == 'MT710')
                        @include('transaction.advice.maker.mt710.edit')
                    @else
                        @include('transaction.advice.maker.mt707.edit')
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4><b>>> Data Tambahan</b></h4>
                        <br>
                        {{ Form::inputText('NO CIF', 'cif', null, null, ['readonly', 'placeholder' => 'click untuk memilih cif', 'required']) }}
                        {{ Form::inputSelect('Cabang', 'branch_code', $branch,null,[],$record->branch) }}
                        {{ Form::inputText('NO Telepon', 'phone', null, null, ['placeholder' => 'masukan no telepon', 'required']) }}
                        {{ Form::inputText('NPWP', 'npwp', null, null, ['placeholder' => 'masukan npwp', 'required']) }}
                        {{ Form::inputSelect('Tujuan L/C', 'lc_purpose', ['L/C Luar Negeri' => 'L/C Luar Negeri', 'L/C Dalam Negeri' => 'L/C Dalam Negeri'],null,[], $record->lc_purpose) }}
                        {{ Form::inputSelect('Jenis L/C', 'lc_type', ['Sight L/C' => 'Sight L/C', 'Usance L/C' => 'Usance L/C', 'Acceptance L/C' => 'Acceptance L/C', 'Negotiation L/C' => 'Negotiation L/C'],null,[],$record->lc_type) }}
                        {{ Form::inputSelect('Jenis Fasilitas', 'facility_type', ['Collection' => 'Collection', 'NWE' => 'NWE'],null,[], $record->facility_type) }}
                    </div>
                    <div class="col-md-6">
                        <h4><b>>> No Rekening</b></h4>
                        <br>
                        {{ Form::inputText('Rekening Biaya', 'rek_export', null, null, ['placeholder' => 'masukan rekening biaya', 'required']) }}
                        {{ Form::inputText('Rekening Hasil Export', 'rek_payment', null, null, ['placeholder' => 'masukan rekening', 'required']) }}

                        <br>
                        <h4><b>>> Charges Advice</b></h4>
                        <div class="form-group">
                            <div class="row">
                                {{-- <div class="col-md-3">
                                    {{ Form::label('charges_a', null, ['class' => 'control-label']) }}
                                </div> --}}
                                <div class="col-md-4">
                                    {!! Form::select('charges_currency_a',$currency,[$record->charges_currency_a],array_merge(['class'=> 'form-control' . \isValid($errors->has('charges_currency_a')),'id'=>'charges_currency_a'])); !!}
                                    <p class="invalid-feedback">{{ $errors->first('charges_currency_a') }}</p>
                                </div>
                                <div class="col-md-8">
                                    {{ Form::inputText(null, 'charges_amount_a', '25', 'format_number', ['required']) }}
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-3">
                                    {{ Form::label('charges_b', null, ['class' => 'control-label']) }}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::select('charges_currency_b',$currency,[$record->charges_currency_b],array_merge(['class'=> 'form-control ' . \isValid($errors->has('charges_currency_b')),'id'=>'charges_currency_b'])); !!}
                                    <p class="invalid-feedback">{{ $errors->first('charges_currency_b') }}</p>
                                </div>
                                <div class="col-md-6">
                                    {{ Form::inputText(null, 'charges_amount_b', '250000', 'format_number', ['required']) }}
                                </div>
                            </div> --}}
                        </div>

                        <br>
                        <h4><b>>> Keterangan Notification Letter</b></h4>
                        <br>
                        {{ Form::inputTextarea(null, 'maker_note', null,null, ['rows' => '7']) }}

                        {{-- yg di hidden --}}
                        <input type="hidden" name="flag_id" id='flag_id' value='1'>
                        <input type="hidden" name="source" value="{{ $type }}">
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

            {{-- MODAL  --}}
            <!-- Modal -->
            <div class="modal fade" id="modalCif" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Data CIF</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text" class="form-control nameCif" placeholder="masukan data..">
                                </div>
                                <div class="col-md-2">
                                    <button type='button' class="btn btn-warning btn-block btnCif">Search</button>
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <table class="table table-bordered tableCif">
                                        <thead>
                                            <tr>
                                                <th width='15%'>CIF No.</th>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // CLICK CIF
    $('#cif').on('click', function () {
        $('.tableCif tbody').empty();
        $('.nameCif').val('');

        $('#modalCif').modal('show');
    });
    // search cif
    $('body').on('click', '.btnCif', function(){
            var name = $('.nameCif').val();
            if(!name){
                swal.fire('Oops!', 'harap masukan data yg di cari', 'warning');
                return false;
            }
            $('.tableCif tbody').empty();
            $.ajax({
                url: '{{ route("apis.dataCif") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    name: name
                },
                success: function(param){
                    if(param.length == 0){
                        swal.fire('Oops','data tidak tersedia', 'warning');
                    }
                    $.each(param, function(key, val){
                        html = '<tr class="dataCif">'
                            + "<td>"+ val.code +"</td>"
                            + "<td>"+ val.name +"</td>"
                            + "<td>"
                                + val.address
                                + "<input type='hidden' id='code' value='"+val.code+"'>"
                                + "<input type='hidden' id='telp' value='"+val.telp+"'>"
                                + "<input type='hidden' id='npwp' value='"+val.npwp+"'>"
                            +"</td>"
                            + "</tr>";

                        $('.tableCif tbody').append(html);
                    });

                },
                error: function(param){
                    console.log(param);
                }
            })

        });

        // click data cif
        $('body').on('click', '.dataCif', function(){
            var code    = $(this).find('#code').val(),
            telp     = $(this).find('#telp').val(),
            npwp    = $(this).find('#npwp').val();

            $('#cif').val(code);
            $('#phone').val(telp);
            $('#npwp').val(npwp);

            $('#modalCif').modal('hide');

        });
        //END CLICK CIF
        $('body').on('click', '.btnUpdate', function(e) {
            e.preventDefault();
            var url     = $('#MyForm').attr('action');;
            var form    = $('#MyForm').serialize();

            swal.fire({
            title: 'Are you sure?',
            text: "It will Update the data !",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
            }).then(function(e) {
                if(e.value){
                    // confirm then
                    $.ajax({
                        url: url,
                        type: 'PUT',
                        dataType: 'json',
                        data: form,
                        success: function (param) {
                            param.code == 'success' ? swal.fire('Success', param.msg, param.code) : '';
                            param.code == 'error'   ? swal.fire('Oops', param.msg, param.code) : '';
                            function myfunc() {
                                window.open('{{ route('apis.notaDebet', Crypt::encrypt($record->id)) }}', '_blank');
                                window.open('{{ route('apis.notificationLetter', Crypt::encrypt($record->id)) }}', '_blank');
                                location.replace("{{ route('advice-makers.index') }}");
                            }

                            if(param.code == 'success'){
                                setTimeout( myfunc, 1200 );
                            }
                        },
                        error: function (param) {
                            swal.fire('Oops', 'Something went wrong!', 'error')
                        }
                    })
                }
            });
        });
</script>
@endpush
