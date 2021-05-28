<style>
    .table thead th, .table thead td {
        font-size: 12px;
    }
    td {
        font-size: 12px !important;
    }

</style>
<!-- Modal-->
<div class="modal fade" id="jurnal-advice" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Biaya Advice LC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>NO</th>
                            <th>TGL TRX</th>
                            <th>JENIS TRANSAKSI</th>
                            <th>LC / SKBDN</th>
                            <th>DB / CR</th>
                            <th>REKENING / GL NO</th>
                            <th>BRANCH</th>
                            <th>GL DESCRIPTION</th>
                            <th>CCY</th>
                            <th>AMOUNT</th>
                            <th>KURS</th>
                            {{-- <th>REMARK</th>
                            <th>STATUS</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>{{ $record->created_at->format('d-F-y') }}</td>
                            <td>ADVICE LC - {{ $record->code }}</td>
                            <td>{{ $record->lc_code }}</td>
                            <td class="text-center">DB</td>
                            <td>{{ $record->currency . '-' . $record->rek_payment }}</td>
                            <td class="text-center">{{ $record->branch_code }}</td>
                            <td>{{ $record->cifmast->CFSNME }}</td>
                            <td class="text-center">{{ $record->currency }}</td>
                            <td align="right">{{ number_format($record->charges_amount_a) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>{{ $record->created_at->format('d-F-y') }}</td>
                            <td>ADVICE LC - {{ $record->code }}</td>
                            <td>{{ $record->lc_code }}</td>
                            <td class="text-center">CR</td>
                            <td>{{ $record->currency == 'IDR' ? '4002000106013' : '4002020106013' }}</td>
                            <td class="text-center">{{ $record->branch_code }}</td>
                            <td>PEND-{{ $record->currency }}-KOM ADVISING</td>
                            <td class="text-center">{{ $record->currency }}</td>
                            <td align="right">{{ number_format($record->charges_amount_a) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
