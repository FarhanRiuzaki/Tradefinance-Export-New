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
                {!! Form::open(array('route' => $route.'.store','method'=>'POST','id' => 'MyForm','enctype'=>'multipart/form-data')) !!}
                    <input type="hidden" name="transactions_id" value="{{ request('data') }}">

                    <div class="row">
                        <div class="col-md-6">
                            {{ Form::inputText('NO SOR', 'code', $code, null,['readonly']) }}
                            <div class="row">
                                <div class="col-md-4">
                                    {{ Form::inputText('Currency', 'currency', $transaction->currency, null,['readonly']) }}
                                </div>
                                <div class="col-md-8">
                                    {{ Form::inputText('Amount', 'amount', number_format(($transaction->amount- $amount_sor),2) , 'format_number',['placeholder' => 'amount..', 'required']) }}
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
                                        <th width='25%'>Upload</th>
                                        <th width='15px'>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-input text-center">
                                        <td colspan="4"><b> Upload Document</b></td>
                                        <td><button type="button" class="btn btn-success btn-icon addFile">
                                                <span class="fas fa-plus"></span>
                                        </button>
                                        </td>
                                    </tr>
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
@push('scripts')
    <script>
    // DOKUMEN
        // click add file
        files_cek   = 0;
        no          = 0;
        key         = 0;
        $('body').on('click', '.addFile', function () {
            no++;
            key++;

            $.ajax({
                url: '{{ route("apis.documentParameter") }}',
                type: 'GET',
                dataType: 'JSON',
                success: function(param){
                    if(param.length == 0){
                        swal.fire('Oops','data tidak tersedia', 'warning');
                    }
                    select  = "<select name='transaction_sors_document["+ key +"][code]' class='form-control doc'>";
                    $.each(param, function (k, v) {
                        select += "<option value='" + v.code + "'>" + v.code + ' - ' + v.name + "</option>";
                    });
                    select  += "</select>";

                    if(no >= 6){
                        no = no-1;
                        swal.fire('Oops', 'maksimal 5 file yang dapat diupload', 'info');
                    }else{
                        html = '<tr>'
                            + "<td class='number text-center'>"
                                + no
                            +"</td>"
                            + "<td>"
                                + select
                            +"</td>"
                            + "<td>"
                                + "<input type='text' data-name='note' name='transaction_sors_document["+key+"][note]' class='form-control'>"
                            +"</td>"
                            + "<td>"
                                + "<input type='file' data-name='document' name='transaction_sors_document["+key+"][document]' class='form-control file'>"
                            + "<td>"
                                + '<button type="button" class="btn btn-danger btn-icon dellFile"><span class="fas fa-times"></span></button>'
                            + "</td>";
                        $('.tableFile tbody').append(html);
                    }
                },
                error: function(param){
                    console.log(param);
                }
            })
        });

        // click dell file
        $('body').on('click', '.dellFile', function () {
            var tr = $(this).closest("tr");
                tr.remove();
                arrayProduct = [];
                var productOldId = tr.find('input[data-name="product_id"]').val();

                $.each($(".tableFile tbody tr:not(.tr-input)"),function(e,item){
                    //ganti nomor per TR
                    var no = e*1 +1;
                    $(this).find(".number").html(no);

                    //NEANGAN INPUTAN PER TR
                    $.each($(this).find("input"),function(s,f){
                        var dataName = $(this).data("name");
                        $(this).attr("name","transactions_document["+e+"]["+dataName+"]");
                    })
                })
                no = no - 1;
                files_cek - 1;
        });

        $('body').on('change', '.file', function () {
            file_name   = this.files[0].name;
            size        = this.files[0].size / 1024;
            limit       = 1024;
            validExtensions = ["pdf", "jpg", "jpeg"];

            extension   = file_name.substr( (file_name.lastIndexOf('.') + 1) );

            change_name = file_name.split('.').shift() + '' + parseInt(Math.random() * 10000) + '.' + extension;
            // set         = $(this).val().replace(file_name, change_name);
            // $(this).val(set);
            // console.log();

            this.files[0].name = change_name;

            valid = true;
            if(validExtensions.indexOf(extension) == -1){
                swal.fire('Oops', 'file harus berektensi: jpg, pdf, jpeg', 'info');
                $(this).val('');
                valid = false;
            }

            if(size > limit){
                swal.fire('Oops', 'file harus berukuran kurang dari 1MB', 'info');
                $(this).val('');
                valid = false;
            }

            if(valid){
                files_cek++;
            }
        });

        // set variable untuk validasi
        amount_sor          = "{{ $amount_sor }}";
        amount_limit        = "{{ $transaction->amount_limit }}";
        amount_maker        = "{{ $transaction->amount }}";
        // set bats atas
        amount_limit  = parseInt(amount_limit);
        amount_limit  = (amount_maker / 100) * amount_limit;
        // set amount trx
        amount_maker        = parseFloat(amount_maker) + parseFloat(amount_limit);

        $('body').on('click', '.btnSubmit', function () {
            amount      = $('#amount').val();
            amount      = parseFloat(amount.split('.').join("")) + parseFloat(amount_sor);

            validate    = true;
            if(files_cek < no){
                msg         = "Pilih Upload file: " + (no - files_cek);
                validate    = false;
            }
            if(no == 0){
                msg         = "Tambahkan dokumen terlebih dahulu.";
                validate    = false;
                $('.addFile').trigger('click');
            }
            if(parseFloat(amount) > amount_maker){
                msg         = "Total amount SOR melebihi amount L/C.";
                validate    = false;
            }
            if(!amount){
                msg         = "Masukan amount terlebih dahulu";
                validate    = false;
            }

            if(validate){
                swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
                }).then((result) => {
                if (result.value) {
                    $('#MyForm').submit();
                }
                });
            }else{
                swal.fire('Oops', msg, 'error');
            }

        })
    </script>
@endpush
