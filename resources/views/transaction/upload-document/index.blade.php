@extends('layout.default')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-custom {{ @$class }}">
            {{-- Header --}}
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ @$page_description }}
                    </h3>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body pt-4 table-responsive" >
                <table class="table table-striped yajra-datatable">
                    <thead>
                        <tr class="text-center">
                            <th width='10px'>No</th>
                            <th>Jenis</th>
                            <th>No L/C & SKBDN</th>
                            <th>CIFNO</th>
                            <th>Cabang</th>
                            <th>Sender</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th width='120px'>Status</th>
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
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route($route. '.index') }}",
        columns: [
            {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            class: "text-center",
            },
            {
            data: 'lc_type',
            name: 'lc_type',
            },
            {
            data: 'lc_code',
            name: 'lc_code',
            },
            {
            data: 'cif',
            name: 'cif',
            class: "text-center",
            },
            {
            data: 'branch',
            name: 'branch',
            },
            {
            data: 'beneficiary',
            name: 'beneficiary',
            },
            {
            data: 'currency',
            name: 'currency',
            class: "text-center",
            },
            {
            data: 'amount',
            name: 'amount',
            },
            {
            data: 'status',
            name: 'status',
            },
            {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            class: 'text-center'
            }
        ],
        "columnDefs": [
            { className: "text-center", "targets": [ 0,3 ] }
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

