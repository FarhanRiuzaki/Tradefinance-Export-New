@extends('layout.default')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 {{ @$class_offset }}">
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
                                <th>Kode</th>
                                <th>ID Cabang</th>
                                <th>Nama Cabang</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
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
            ajax: "{{ route('branchs.index') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {data: 'code',      name: 'code'},
                {data: 'branch',    name: 'branch'},
                {data: 'name',      name: 'name'},
                {data: 'phone',     name: 'phone'},
                {data: 'address',   name: 'address'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ],
            "columnDefs": [
                { className: "text-center", "targets": [ 0,6 ] }
            ]
        }).on( 'draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
            });

      });
    </script>
@endpush
