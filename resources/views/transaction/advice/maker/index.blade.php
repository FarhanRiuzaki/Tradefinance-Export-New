@extends('layout.default')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-custom {{ @$class }}">
            {{-- Header --}}
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ @$page_description }} - <span id="typeTitle" class="text-primary  text-uppercase font-weight-bold">MT700</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <div class="dropdown dropdown-inline">
                        <a href="#" class="btn btn-light-primary btn-sm font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sumber Data: (default MT700)
                        </a>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            {{-- Navigation --}}
                            <ul class="navi navi-hover">
                                <li class="navi-header">
                                    <span class="text-primary text-uppercase font-weight-bold">Pilih Sumber:</span>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link typeBtn" data-type='MT700'>
                                        <i class="navi-icon flaticon2-graph-1"></i>
                                        <span class="navi-text">MT700</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link typeBtn" data-type='MT710'>
                                        <i class="navi-icon flaticon2-graph-1"></i>
                                        <span class="navi-text">MT710</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link typeBtn" data-type='MT707'>
                                        <i class="navi-icon flaticon-file-2"></i>
                                        <span class="navi-text">MT707 (Amend)</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link typeBtn" data-type='DOCUMENT'>
                                        <i class="navi-icon flaticon2-layers-1"></i>
                                        <span class="navi-text">Document</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body pt-4 table-responsive" >
                <table class="table table-striped yajra-datatable">
                    <thead>
                        <tr class="text-center">
                            <th width='30px'>No</th>
                            <th>No LC</th>
                            <th>Beneficiary</th>
                            <th>Sender</th>
                            <th>Curency</th>
                            <th>Ammount</th>
                            <th width='100px'>Created At</th>
                            <th width='80px'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            {{-- hidden input  --}}
            <input type="hidden" name='type' id="type">
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(function () {
    @if (@session('msg')['id'])
        window.open('{{ route('apis.notaDebet', Crypt::encrypt(session('msg')['id'])) }}', '_blank');
        window.open('{{ route('apis.notificationLetter', Crypt::encrypt(session('msg')['id'])) }}', '_blank');
    @endif
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route($route.'.datatable') }}',
            type: 'GET',
            data: {
                type: function() { return $('#type').val() },
            }
        },
        // ajax: "{{ route($route.'.datatable') }}",
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {data: 'no_lc',         name: 'no_lc'},
            {data: 'beneficiary',   name: 'beneficiary'},
            {
            data: 'sender',
            name: 'sender',
            },
            {data: 'currency',      name: 'currency'},
            {data: 'amount',        name: 'amount'},
            {data: 'created_at',    name: 'created_at'},
            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: true,
                className: "text-center"
            },
        ],
        "columnDefs": [
            { className: "text-center", "targets": [ 0,4 ] }
        ]
    }).on( 'draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    });

    $('.typeBtn').on('click', function() {
        var type = $(this).data('type');

        $('#type').val(type);
        reloadDatatable();
        $('span#typeTitle').html(type)
    });

    function reloadDatatable() {
        $('.yajra-datatable').DataTable().ajax.reload();
    }

</script>
@endpush
@php
    Session::forget('msg');
@endphp
