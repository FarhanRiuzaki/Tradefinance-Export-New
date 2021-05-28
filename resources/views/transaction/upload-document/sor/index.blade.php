@extends('layout.default')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-custom {{ @$class }}">
            {{-- Header --}}
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">List SOR
                    </h3>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body pt-4 table-responsive" >
                <table class="table table-striped yajra-datatable">
                    <thead>
                        <tr class="text-center">
                            <th width='10px'>No</th>
                            <td>NO SOR</td>
                            <td>Amount</td>
                            <td>Currency</td>
                            <td>Status L/C SKBDN</td>
                            <td>Status SOR</td>
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
        ajax: "{{ route($route. '.indexSor', request('id')) }}",
        columns: [
            {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            class: "text-center",
            },

            {
            data: 'code',
            name: 'code',
            },
            {
            data: 'amount',
            name: 'amount',
            },
            {
            data: 'currency',
            name: 'currency',
            class: "text-center",
            },
            {
                data: 'status_lc',
                name: 'status_lc',
                class: "text-center",
            },
            {
            data: 'status_sor',
            name: 'status_sor',
            class: "text-center",
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
</script>
@endpush

