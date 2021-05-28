<!doctype html>
<html>
    @php
        if ($record->source == 'MT707') {
            $records = $record->mt707;
        }elseif ($record->source == 'MT710') {
            $records = $record->mt710;
        }elseif ($record->source == 'MT700') {
            $records = $record->mt700;
        }
    @endphp
<head>
    <meta charset="utf-8">
    <title>Notificaion Letter</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            /* padding: 30px; */
            /* border: 1px solid #eee; */
            /* box-shadow: 0 0 10px rgba(0, 0, 0, .15); */
            font-size: 16px;
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
        .footer {
            position: fixed;
            left: 0;
            bottom: 80;
            width: 100%;
            text-align: center;
            font-size: 13px !important;
        }
        .address{
            font-size: 8px;
            bottom: 10;
        }
    </style>
	{{-- <link href="{{ asset('css/bundle.css') }}" rel="stylesheet"> --}}
</head>
<body>
    @php
        function jd_to_gd_rchrds ($J) {
        $y=4716;
        $v=3;
        $j=1401;
        $u=5;
        $m=2;
        $s=153;
        $n=12;
        $w=2;
        $r=4;
        $B=274277;
        $p=1461;
        $C=-38;

        $f = $J + $j + intdiv(intdiv(4 * $J + $B,146097) * 3,4) + $C;

        $e = $r * $f + $v;
        $g = intdiv(($e % $p) , $r);

        $h = $u * $g + $w;
        $D = intdiv((($h%$s)) , $u) + 1;
        $M = ((intdiv($h , $s) + $m) % $n) + 1;
        $Y = intdiv($e , $p) - $y + intdiv($n + $m - $M,$n);

        return "$M/$D/$Y";
        }
    @endphp
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td>
                    <table>
                        <tr>
                            <td class="title" width='500px'>
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
                        ORIGINAL <br> NOTIFICATION OF DOCUMENT CREDIT
                    </h3>
                </td>
            </tr>
            <tr class="isi">
                <td>
                    <table>
                        <tr>
                            <td width='120px'>DATE</td>
                            <td width='5px'>:</td>
                            <td>{{ date("F d, Y", strtotime($record->created_at)) }}</td>
                        </tr>
                        <tr>
                            <td>REF. NO</td>
                            <td>:</td>
                            <td>{{ $record->code }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="isi">
                <td>
                    <table>
                        <tr>
                            <td width='50%'>
                                TO: <br>
                                BENEFICIARY: <br>
                                {!! nl2br($record->beneficiary) !!}
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="isi">
                <td>
                    <table>
                        <tr>
                            <td rowspan="6" width="50%">
                                ISSUING BANK: <br>
                                @if ($record->source == 'MT700')
                                    {!! nl2br($record->mt700->sender) !!}
                                @elseif ($record->source == 'MT707')
                                    {!! nl2br($record->mt707->sender) !!}
                                @else
                                    {!! nl2br($records->F52A) !!}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td >L/C NO.</td>
                            <td>:</td>
                            <td>{{ $record->lc_code }}</td>
                        </tr>
                        <tr>
                            <td>AMOUNT</td>
                            <td>:</td>
                            <td>{{ $record->currency . ' ' . number_format($record->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>DATE OF ISSUE</td>
                            <td>:</td>
                            <td>
                                {{ convertDateMT($records->F31C) }}
                            </td>
                        </tr>
                        <tr>
                            <td>LATEST SHIPMENT</td>
                            <td>:</td>
                            <td>
                                {{ convertDateMT($records->F44C) }}
                            </td>
                        </tr>
                        <tr>
                            <td>EXPIRY DATE</td>
                            <td>:</td>
                            <td>
                                {{ convertDateMT($records->F31D) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <br>
                </td>
            </tr>
            <tr class="isi">
                <td>DEAR SIR</td>
            </tr>
            <tr class="isi">
                <td>WE HAVE BEEN INFORMED BY TE ABOVE-MENTIONED ISSUING BANK THAT THE ABOVE-MENTIONED DOCUMENTARY CREDIT HAS BEEN ISSUED IN YOUR FAVOUR. <br>
                PLEASE FIND ENCLOSED THE ADVICE INTENDED FOR YOU.</td>
            </tr>
            <tr class="isi">
                <td>
                    CHECK THE CREDIT TERMS AND CONDITIONS CAREFULLY.  IN THE EVENT YOU DO NOT AGREE WITH THE TERMS AND CONDITIONS, OR IF YOU FEEL UNABLE TO COMPLY WITH ANY OF THOSE TERMS AND CONDITIONS, KINDLY ARRANGE AN AMENDMENT OF THE CREDIT THROUGH YOUR CONTRACTING PARTY (THE APPLICANT).
                </td>
            </tr>
            <tr class="isi">
                <td>
                    THIS NOTIFICATION AND THE ENCLOSED ADVICE ARE SENT TO YOU WITHOUT ANY ENGAGEMENT ON OUR PART.
                </td>
            </tr>
            <tr class="isi">
                <td>WE HAVE NO RESPONSIBILITY FOR ANY ERRORS, OMISSIONS AND DELAYS IN THE TRANSMISSION.</td>
            </tr>
            <tr class="isi">
                <td>BEING OUR ADVISING COMMISSION, WE WILL DEBIT YOUR ACCOUNT FOR USD 25 AND IDR 250.000 </td>
            </tr>
            <tr class="isi">
                <td>THIS ADVICE IS SUBJECT TO UNIFORM CUSTOMS AND PRACTICE FOR DOCUMENTARY CREDITS 2007 REVISION, INTERNATIONAL CHAMBER OF COMMERCE PUBLICATION NO 600.</td>
            </tr>

        </table>
        <div class="footer">
            <p> THIS IS A COMPUTER GENERATED ADVICE. MANUAL SIGNATURE IS NOT REQUIRED
            </p>
            {{-- <p class='address'>
                PT Bank Mega Tbk, Head Office Attn. Trade Operations, Menara Bank Mega 16th Floor, Jl. Kapten Tendean 12-14A, Jakarta 12790 Indonesia. Phone: +62-021-7917500 SWIFT Code. MEGAIDJAXXX
            </p> --}}
        </div>
    </div>
</body>
</html>
