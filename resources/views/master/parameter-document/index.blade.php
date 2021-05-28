@extends('layout.default')

@section('content')
<div class="row">
    @php
        $class_offset = 'offset-2';
    @endphp
    @can('permission.create')
        @php
            $class_offset = '';
        @endphp
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="card card-custom {{ @$class }}">
                {{-- Header --}}
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                    </h3>
                    <h3 class="card-label">Add Paramater Document
                    </div>
                </div>
                {{-- Body --}}
                <div class="card-body pt-4" >
                    @if(isset($edit))
                        @include('master.parameter-document.edit')
                    @else
                        @include('master.parameter-document.create')
                    @endif
                </div>
            </div>
        </div>
    @endcan
    <div class="col-xs-8 col-sm-8 col-md-8 {{ @$class_offset }}">
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
                <table class="table table-bordered yajra-datatable">
                    <thead>
                        <tr class="text-center">
                            <th width='30px'>No</th>
                            <th>Code</th>
                            <th>name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th width='100px'>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
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
                searchable: false
            },
            {data: 'code',  name: 'code'},
            {data: 'name',  name: 'name'},
            {data: 'status',name: 'status'},
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
            { className: "text-center", "targets": [ 0,1,3 ] }
        ]
    }).on( 'draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

  });
</script>
@endpush
