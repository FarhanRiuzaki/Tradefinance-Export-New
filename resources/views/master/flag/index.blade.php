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
                    <h3 class="card-label">Add Flag
                    </div>
                </div>
                {{-- Body --}}
                <div class="card-body pt-4" >
                    {!! Form::open(array('route' => 'flags.store','method'=>'POST','id' => 'MyForm')) !!}

                    {{ Form::inputNumber('Parent*',     'parent',       null, null, ['placeholder' => 'input parent', 'required']) }}
                    {{ Form::inputNumber('Level*',      'level',        null, null, ['placeholder' => 'input level', 'required']) }}
                    {{ Form::inputText('Deskripsi*',    'description',  null, null, ['placeholder' => 'input description', 'required']) }}

                    <button onclick="CheckValidation();" type="submit" id="btn-submit" class="btn font-weight-bold btn-block btn-primary">
                            Submit Permission
                    </button>

                    {!! Form::close() !!}

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
                            <th width='30px'>ID</th>
                            <th>Parent</th>
                            <th>Level</th>
                            <th>Description</th>
                            <th>Created At</th>
                            {{-- <th width='100px'>Action</th> --}}
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
        ajax: "{{ route('flags.index') }}",
        columns: [
            {data: 'id',            name: 'id'},
            {data: 'parent',        name: 'parent'},
            {data: 'level',         name: 'level'},
            {data: 'description',   name: 'description'},
            {data: 'created_at',    name: 'created_at'},
            // {
            //     data: 'action',
            //     name: 'action',
            //     orderable: true,
            //     searchable: true
            // },
        ],
        "columnDefs": [
            { className: "text-center", "targets": [ 0,1,2 ] }
        ]
    }).on( 'draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

  });
</script>
@endpush
