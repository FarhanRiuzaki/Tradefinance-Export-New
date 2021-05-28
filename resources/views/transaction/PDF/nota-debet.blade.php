<!DOCTYPE html>
<html>
<head>
	<title>Nota Debet</title>
</head>
<style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        /* padding: 30px; */
        /* border: 1px solid #eee; */
        /* box-shadow: 0 0 10px rgba(0, 0, 0, .15); */
        font-size: 14px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }

    .invoice-box table {
        width: 100%;
        line-height: normal; /* inherit */
        text-align: left;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }

        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }

    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .rtl table {
        text-align: right;
    }

    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    .center{
        text-align: center;
    }
    .ls{
        letter-spacing: 3px;
    }
    .isi{
        font-size: 13px !important;
        text-align: justify;
    }
    .isi td table tr td{
        padding: 0;
        margin: 0;
    }
    .invoice-box table tr td{
        padding: 10px;
    }

    .footer {
        position: fixed;
        left: 0;
        bottom: 20;
        width: 100%;
        text-align: center;
            font-size: 13px !important;
    }
</style>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td>
                    <table>
                        <tr>
                            <td class="title" width='470px'>
                                <img src="{{ public_path('img/BM1.png') }}" width="120px">
                            </td>
                            <td style="text-align: left; font-size: 11px">
                                <b>Head Office</b>
                                <br>
                                Menara Bank Mega
                                <br>
                                Jl. Kapten Tendean Kav. 12-14A
                                <br>
                                Jakarta 12790 Indonesia
                                <br>
                                <b>T</b> +62 21 7919 5000
                                <br>
                                SWIFT Code. MEGAIDJAXXX
                                <br>
                                www.bankmega.com
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="center">
                    <h3 class="ls">
                        NOTA DEBET
                    </h3>
                </td>
            </tr>
        </table>
        <br>
        <table width="70%" cellspacing="0">
            <tr>
                <td width='50%'>CABANG : {{ $record->branchs->code . ' - ' . $record->branchs->name }}</td>
                <td></td>
                <td  width='35%'>TANGGAL : {{ date("Y-m-d", strtotime($record->created_at)) }}</td>
            </tr>
        </table>
        </center>
        <center>
        <table width="70%" height ="200px" border = "1" cellspacing="0">
            <tr>
                <td rowspan="2" width="40%">Kepada :
                    <br>
                    {!! nl2br($record->cifmast->CFSNME) !!}
                    <br>
                    {{ $record->cifmast->CFNA2 . ' ' . $record->cifmast->CFNA3 . ' ' . $record->cifmast->CFNA4 . ' '. $record->cifmast->CFNA5 }}
                </td>
                <td width="5%" style="text-align: left">Rekening NO : </td>
                <td width="50%" align="right">{{ $record->rek_payment }}</td>
            </tr>
            <tr>
                <td>Atas Nama : </td>
                <td>
                    {{ $record->cifmast->CFSNME }}
                </td>
            </tr>
            <tr>
                <td colspan="2" height="50px">Telah didebet dari rekening Saudara mengenai : <br> Advice LC dengan No. <br> {{ $record->code }} </td>
                <td style="text-align: left">Jumlah yang didebet :
                    {{ ucwords(terbilang($record->charges_amount_a)) }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Keterangan:
                    <br>
                    1. Biaya Advise LC No. {{ $record->lc_code }}
                    <br>
                    <br>
                    2. __________________
                    <br>
                    <br>
                    3. __________________
                    <br>
                    <br>
                    {{-- 4. Biaya_______________________________________ --}}
                </td>
                <td height="50px" style="text-align: left">{{ $record->currency }}.
                    <br>
                    <br>
                    <h2 align='right'>
                        {{ number_format($record->charges_amount_a, 2) }}
                    </h2>
                </td>
            </tr>
            {{-- <tr>
                <td height = "100px"></td>
            </tr> --}}
        </table>
    </center>
        <div class="footer">
            <p> THIS IS A COMPUTER GENERATED ADVICE. MANUAL SIGNATURE IS NOT REQUIRED
            </p>
        </div>
    </div>
</body>
</html>
