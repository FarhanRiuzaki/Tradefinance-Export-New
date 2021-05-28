@php
    if ($record->source == 'MT707') {
        $records = $record->mt707;
    }elseif ($record->source == 'MT710') {
        $records = $record->mt710;
    }elseif ($record->source == 'MT700') {
        $records = $record->mt700;
    }
@endphp
<!-- Modal-->
<div class="modal fade" id="mt730" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">MT730</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <td>F20</td>
                        <td>:</td>
                        <td>{{ $record->code }}</td>
                    </tr>
                    <tr>
                        <td>F21</td>
                        <td>:</td>
                        <td>{{ $record->lc_code }}</td>
                    </tr>
                    <tr>
                        <td>F30</td>
                        <td>:</td>
                        <td>{{ $records->F31C }}</td>
                    </tr>
                    <tr>
                        <td>F79Z</td>
                        <td>:</td>
                        <td>LC ADVICE ON {{ $record->created_at->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
