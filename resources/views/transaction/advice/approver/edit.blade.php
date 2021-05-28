@extends('layout.default')
<style>
    .form-group {
        margin-bottom: .5rem !important;
    }
</style>
@section('content')

@include('transaction.jurnal.jurnal-advice')
@include('transaction.jurnal.mt730')

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
                            Generate Data
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
                                <li class="navi-item">
                                    <a href="#" class="navi-link"data-toggle="modal" data-target="#mt730"">
                                        <i class="navi-icon flaticon-file-2"></i>
                                        <span class="navi-text">MT730</span>
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
                        @include('transaction.advice.approver.mt700.edit')
                    @elseif ($record->source == 'MT710')
                        @include('transaction.advice.approver.mt710.edit')
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
                        <h4><b>>> Charges Jurnal</b></h4>
                        <div class="form-group">
                            <div class="row">
                                {{-- <div class="col-md-3">
                                    {{ Form::label('charges_a', null, ['class' => 'control-label']) }}
                                </div> --}}
                                <div class="col-md-3">
                                    {{ Form::inputText(null, 'charges_currency_a', null, null, ['disabled']) }}
                                </div>
                                <div class="col-md-6">
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
                        <input type="hidden" name="source" id='source' value="{{ $record->source }}">
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

            {{-- hidden input  --}}
            <input type="hidden" name='type' id="type">

        </div>
    </div>
</div>

 <!-- Modal-->
 <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Catatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::inputTextarea(null, 'approve_note', null,null, ['placeholder' => 'masukan catatan','rows' => '4', null, null, 'required']) }}
                <label id="parent-error" class="error" for="parent" style="display: none;">This field is required.</label>
                <br>
                <label id="parent-error" class="error text-primary" for="parent">Note*: klik "Generate Data" untuk melihat Notification Letter, Nota Debet, Jurnal, & MT730</label>
                <input type="hidden" name="flag_id" id="flag_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn font-weight-bold btnSubmit"></button>
            </div>
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
        $('body').on('click', '.btnApprove', function(e) {
            $('#exampleModal').modal('show');
            $('.btnSubmit').html('Approve');
            $('.btnSubmit').addClass('btn-success');
            var source = $('#source').val();
            if(source == 'MT707'){
                $('#flag_id').val('5'); // AMEND - Approve
            }else{
                $('#flag_id').val('2'); // ADVICE - Approve
            }
        });
        $('body').on('click', '.btnReject', function(e) {
            $('#exampleModal').modal('show');
            $('.btnSubmit').html('Reject');
            $('.btnSubmit').addClass('btn-danger');
            var source = $('#source').val();
            if(source == 'MT707'){
                $('#flag_id').val('6'); // AMEND - Approve
            }else{
                $('#flag_id').val('3'); // ADVICE - Reject
            }
        });

        $('.btnSubmit').on('click', function() {
            approve_note    = $('#approve_note').val();
            flag            = $('#flag_id').val();
            type            = flag  == 2 || 5 ? 'Approve' : 'Reject';

            if(!approve_note){
                swal.fire('Oops', 'masukan catatan', 'info')
                return false;
            }
            swal.fire({
            title: 'Are you sure?',
            text: "anda akan "+type+" Transaksi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
            }).then(function(e) {
                if(e.isConfirmed){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route('advice-approvers.update', Crypt::encrypt($record->id)) }}',
                        type: 'PUT',
                        dataType: 'json',
                        data: {
                            submit: true,
                            _token: "{{ csrf_token() }}",
                            flag_id: flag,
                            approve_note: approve_note,
                        },
                        success: function (param) {
                            param.code == 'success' ? swal.fire('Success', param.msg, param.code) : '';
                            param.code == 'error'   ? swal.fire('Oops', param.msg, param.code) : '';

                            function myfunc() {
                                if(param.type == 'Approve'){
                                    window.open('{{ route('apis.notaDebet', Crypt::encrypt($record->id)) }}', '_blank');
                                    window.open('{{ route('apis.notificationLetter', Crypt::encrypt($record->id)) }}', '_blank');
                                }
                                location.replace("{{ route('advice-approvers.index') }}");
                            }

                            if(param.code == 'success'){
                                setTimeout( myfunc, 1200 );
                            }
                        },
                        error: function (param) {
                            swal.fire('Oops', 'Something went wrong!', 'error');
                        }
                    })
                }
            });
        })

    </script>
@endpush
