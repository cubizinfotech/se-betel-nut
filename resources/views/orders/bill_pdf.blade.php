<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati&display=swap" rel="stylesheet">

    <title>Estimate - A4 Invoice</title>
    <style>
        @page {
            size: A4;
            margin-top: 10mm;
            margin-bottom: 13mm;
        }
        /* body {
    font-family: 'Noto Sans Gujarati', 'Shruti', sans-serif;
} */

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
        }

        .sheet {
            /* width: 210mm;
      min-height: 297mm; */
            /* padding: 15mm; */
            margin: auto;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
            vertical-align: middle;
        }

        .no-border td,
        .no-border th {
            border: none;
        }

        .title {
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .particulars {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            /* white-space: pre-wrap; */
            line-height: 1.2;
        }

        .particulars p span {
            font-size: 12px;
            width: 35px;
            display: inline-block;
            border: 1px solid #ddd;
            padding: 3px;
            margin: 0 0 2px;
            text-align: center;
            line-height: 12px;
        }

        .particulars p span.total {
            background: #ddd;
        }

        .top_wrapper tr td {
            font-size: 16px;
        }

        p {
            margin: 0;
        }

        .main_table tr td {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="sheet">
        <table class="no-border">
            <tr>
                <td style="padding: 0;">
                    <table class="no-border top_wrapper" style="background: #fff; ">
                    <tr>
                        <td class="center" style="color:#000; text-align:center; border-collapse: collapse;">
                            <!-- First image with border -->
                            <div style="padding:4px;width:100%; border:1px solid #000; box-sizing:border-box; margin-bottom:2px;">
                            <div style=" ">
                                <img src="{{ public_path('img/Screenshot_5.png') }}" style="width:30px; height:auto; display:block; margin:0 auto;">
                            </div>
                            <!-- Second image with border -->
                            <div>
                                <img src="{{ public_path('img/Screenshot_6.png') }}" style="width:100px; height:auto; display:block; margin:0 auto;">
                            </div>
                        </div>
                            <!-- Third: ESTIMATE label with border -->
                            <div style="padding:4px; width:100%; border:1px solid #000; box-sizing:border-box; font-size:12px;">
                                ESTIMATE
                            </div>
                        </td>
                    </tr>
                    {{-- <br> --}}
                    {{-- <table class="no-border" style="border-bottom: 1px solid #000; color: #fff; background: #000;">
                        <tr>
                            <td class="title center" style="width: 33.333%; color: #fff;">ESTIMATE</td>
                        </tr>
                    </table> --}}
                </td>
            </tr>
        </table>

        <table class="no-border" style="margin-top:3px; background: #f3f3f3;">
            <tr>
                <td>
                    <p style="margin-bottom: 3px;">SR. No: <strong>{{ $order->id }}</strong></p>
                    <p>To: <strong>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</strong></p>
                </td>
                <td class="right">
                    <p style="margin-bottom: 3px; text:start;">Date: <strong>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</strong></p>
                    <p style="margin-right:7px;">Time: <strong>{{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</strong></p>
                </td>
            </tr>
        </table>

        <table class="no-border" style="margin-top:5px; background: #f3f3f3;">
            <tr>
                <td>
                    <p style="margin-bottom: 3px;">A/C:- ______________________</p>
                    <p>GARAGE:- ______________________</p>
                </td>
                <td class="right">
                    <p style="margin-bottom: 3px;">Br.: <strong>SELF</strong></p>
                    <p>LR. NO: <strong>{{ $order->lot_number ?? $order->order_number }}</strong></p>
                </td>
            </tr>
        </table>

        <table style="margin-top:3px; border-collapse: separate;" class="main_table">
            <tr>
                <th style="background: #fff; color: #000;">Particulars</th>
                <th style="background: #fff; color: #000; width: 50px;">Bag</th>
        <th style=" background: #fff; color: #000; width: 60px;">Net.Wt.</th>
                <th style="background: #fff; color: #000; width: 70px;">Rate</th>
        <th style=" background: #fff; color: #000; width: 110px;">Amount</th>
            </tr>
            <tr>
                <td class="particulars" style="background: #f3f3f3;">
                    <p style="font-size: 12px; margin: 0 0 4px;">{{ strtoupper($order->product_name) }}</p>
                    @php
                        $perBagWeights = json_decode($order->per_bag_weight, true);
                    @endphp

                    @if($perBagWeights && is_array($perBagWeights))
                        @foreach($perBagWeights as $weight)
                            <span style="margin: 0 0 4px;font-size: 8px;line-height: 10px;border: 1px solid #ddd; width: 35px;">{{ number_format($weight, 1) }},</span>
                            {{-- @if(!$loop->last)
                                <strong>,</strong>
                            @endif --}}
                        @endforeach
                    @endif


                </td>
                <td class="center" style="background: #f3f3f3;">{{ $order->quantity }}</td>
                <td class="center" style="background: #f3f3f3;">{{ number_format($order->total_weight, 1) }}</td>
                <td class="center" style="background: #f3f3f3;">{{ number_format($order->rate, 2) }}</td>
                <td class="right" style="background: #f3f3f3;">{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="border: 0;"></td>
                <td colspan="3" style="background: #f3f3f3;">
                   <strong> Sub Total</strong>
                </td>
                <td class="right" style="background: #f3f3f3;">
                    {{ number_format($order->total_amount, 2) }}
                </td>
            </tr>
            <tr>
                <td style="border: 0;"></td>
                <td colspan="3" style="background: #f3f3f3;"><strong>Packaging Charge</strong></td>
                <td class="right" style="background: #f3f3f3;">{{ number_format($order->packaging_charge, 2) }}</td>
            </tr>
            <tr>
                <td style="border: 0;"></td>
                <td colspan="3" style="background: #f3f3f3;"><strong>Hanali Charge</strong></td>
                <td class="right" style="background: #f3f3f3;">{{ number_format($order->hamali_charge, 2) }}</td>
            </tr>
            <tr>
                <td style="border: 0;"></td>
                <td colspan="3" style="background: #f9c600; font-size: 10px; font-weight: 600;"><strong>Grand Total</strong></td>
                <td class="right" style="background: #f9c600; font-size: 10px; font-weight: 600;">
                    <strong>{{ number_format($order->grand_amount, 2) }}</strong>
                </td>
            </tr>
        </table>

        <table class="no-border" style="margin-top:2px;">
            <tr>
                <td class="center">
                    <p>Payment condition within 3 days</p>
                    <p>This is a computer-generated estimate</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
