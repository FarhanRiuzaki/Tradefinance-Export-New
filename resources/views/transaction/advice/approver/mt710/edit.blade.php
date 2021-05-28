<style>
    .table th, .table td {
        padding: 2px;
        vertical-align: top;
        border-top: 0;
    }
    .table .form-group {
        margin: 0;
    }
    td{
        font-size: 14px;
    }
</style>

<div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="accordionExample7">
    <div class="card">
        <div class="card-header" id="headingTwo7">
            <div class="card-title collapsed" data-toggle="modal" data-target="#exampleModalLong">
                <span class="svg-icon svg-icon-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                            <path
                                d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                fill="#000000" fill-rule="nonzero"></path>
                            <path
                                d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                fill="#000000" fill-rule="nonzero" opacity="0.3"
                                transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) ">
                            </path>
                        </g>
                    </svg>
                </span>
                <div class="card-label pl-4">File Asli {{ $record->source }}</div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo7">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo7">
                <span class="svg-icon svg-icon-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                            <path
                                d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                fill="#000000" fill-rule="nonzero"></path>
                            <path
                                d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                fill="#000000" fill-rule="nonzero" opacity="0.3"
                                transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) ">
                            </path>
                        </g>
                    </svg>
                </span>
                <div class="card-label pl-4">{{ $record->source }}</div>
            </div>
        </div>
        <div id="collapseTwo7" class="collapse" data-parent="#accordionExample7">
            <div class="card-body pl-12">
                <div class="row">
                    <div class="col-md-6">
                        <table width='100%' class='table'>
                            <tr style='vertical-align: middle'>
                                <td width='5%' class='text-center'>20</td>
                                <td width='40%'>Documentary Credit Number</td>
                                <td  width='5%' class='text-right'>: </td>
                                <td width='50%'>
                                    {{ Form::inputText(null,null, $record->mt710->F20, null, ['readonly'])}}
                                </td>
                            </tr>
                            <tr>
                                <td class='text-center'>31C</td>
                                <td>Date of Issue</td>
                                <td class='text-right'>: </td>
                                <td>
                                    {{ Form::inputText(null,null, $record->mt710->F31C, null, ['readonly'])}}
                                </td>
                            </tr>
                            <tr>
                                <td class='text-center'>31D</td>
                                <td>Date and Place of Expiry</td>
                                <td class='text-right'>: </td>
                                <td>
                                    {{ Form::inputText(null,null, $record->mt710->F31D, null, ['readonly'])}}
                                </td>
                            </tr>
                            <tr>
                                <td class='text-center'>50</td>
                                <td>Applicant</td>
                                <td class='text-right'>: </td>
                                <td>
                                    {{ Form::inputTextarea(null,null,  $record->mt710->F50, null, ['rows' => '4', 'readonly'])}}

                                </td>
                            </tr>
                            <tr>
                                <td class='text-center'>59</td>
                                <td>Beneficiary</td>
                                <td class='text-right'>: </td>
                                <td>
                                    {{ Form::inputTextarea(null,null,  $record->mt710->F59, null, ['rows' => '4', 'readonly'])}}
                                </td>
                            </tr>
                            <tr>
                                <td class='text-center'>32B</td>
                                <td>Currency Code, Amount</td>
                                <td class='text-right'>: </td>
                                <td>
                                    {{ Form::inputTextarea(null,null, $record->mt710->F32B, null, ['rows' => '2','readonly'])}}
                                    <input type="hidden" name="transactions_{{ Str::lower($record->source) }}[F32B_AMT]" value="{{ $record->mt710->F32B_AMT }}">
                                    <input type="hidden" name="transactions_{{ Str::lower($record->source) }}[F32B_CUR]" value="{{ $record->mt710->F32B_CUR }}">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr style='vertical-align: middle'>
                                <td width='5%' class='text-center'>42A</td>
                                <td width='40%'>Drawee</td>
                                <td  width='5%' class='text-right'>: </td>
                                <td width='50%'>
                                    {{ Form::inputText(null,null, $record->mt710->F42A, null, ['readonly'])}}
                                </td>
                            </tr>
                            <tr>
                                <td class='text-center'>39A</td>
                                <td>PercenFe Credit Amount Tolerance</td>
                                <td class='text-right'>: </td>
                                <td>
                                    {{ Form::inputText(null,null, $record->mt710->F39A, null, ['readonly'])}}
                                </td>
                            </tr>
                            <tr>
                                <td class='text-center'>42C</td>
                                <td>Drafts at ...</td>
                                <td class='text-right'>: </td>
                                <td>
                                    {{ Form::inputText(null,null, $record->mt710->F42C, null, ['readonly'])}}
                                </td>
                            </tr>
                            <tr>
                                <td class='text-center'>44A</td>
                                <td>Late Shipment Date</td>
                                <td class='text-right'>: </td>
                                <td>
                                    {{ Form::inputTextarea(null,null, $record->mt710->F44A, null, ['rows' => '4', 'readonly'])}}
                                </td>
                            </tr>
                            <tr>
                                <td class='text-center'>52A</td>
                                <td>Issuing Bank</td>
                                <td class='text-right'>: </td>
                                <td>
                                    {{ Form::inputTextarea(null,null, $record->mt710->F52A, null, ['rows' => '4', 'readonly'])}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<br>
</div>

<!-- Modal-->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">File Asli {{ $record->source  }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 500px;">
                @php
                    echo '<code>';
                    echo '<pre>';

                    echo $show_file[Str::lower($record->source)];

                    echo '</pre>';
                    echo '</code>';
                @endphp
            </div>
        </div>
    </div>
</div>
