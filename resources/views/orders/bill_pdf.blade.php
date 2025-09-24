<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estimate - A4 Invoice</title>
    <style>
        @page {
            size: A4;
            margin-top: 13mm;
            margin-bottom: 13mm;
        }

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
            font-size: 20px;
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
        <table class="no-border" style="background: #000;">
            <tr>
                <td style="padding: 0;">
                    <table class="no-border top_wrapper">
                        <tr>
                            <td class="center"
                                style="padding:10px; color:#fff; border-bottom:1px solid #272727;">
                                <span>Shree Ganesha Namah</span>
                            </td>

                        </tr>
                    </table>
                    <table class="no-border" style="border-bottom: 1px solid #000; color: #fff;">
                        <tr>
                            <td style="width: 33.333%; color: #fff;">SR No <strong>{{ $order->id }}</strong></td>
                            <td class="title center" style="width: 33.333%; color: #fff;">ESTIMATE</td>
                            <td class="right" style="width: 33.333%; color: #fff;">
                                <p style="margin: 0 0 5px;">Date: <strong>28/08/2025</strong></p>
                                <p>Branch: <strong>{{ $order->order_number }}</strong></p>
                            </td>
                        </tr>
                    </table>


                </td>
            </tr>
        </table>

        <table class="no-border" style="margin-top:10px; background: #f3f3f3;">
            <tr>
                <td>
                    <p style="margin-bottom: 5px;">To, <strong>{{ $order->customer->first_name }}
                            {{ $order->customer->last_name }}</strong></p>
                    <p>A/C: <strong>{{ $order->customer->id }}</strong></p>
                </td>
                <td class="right">
                    <p style="margin-bottom: 5px;">GARAGE: <strong> </strong></p>
                    <p>LR. NO: <strong>{{ $order->lot_number }}</strong></p>
                </td>
            </tr>
        </table>


        <table style="margin-top:10px; border-collapse: separate;" class="main_table">
            <tr>
                <th style="background: #000; color: #fff;">Particulars</th>
                <th style="background: #000; color: #fff; width: 50px;"">Bag</th>
        <th style=" background: #000; color: #fff; width: 60px;"">Net.Wt.</th>
                <th style="background: #000; color: #fff; width: 70px;"">Rate</th>
        <th style=" background: #000; color: #fff; width: 110px;">Amount</th>
            </tr>
            <tr>
                <td class="particulars" style="background: #f3f3f3;">
                    <p style="font-size: 14px; margin: 0 0 5px;">{{ strtoupper($order->product_name) }}</p>
                    @php
                        // Parse the per_bag_weight JSON and display weights
                        $perBagWeights = json_decode($order->per_bag_weight, true);
                        $totalWeight = 0;
                        $bagCount = 0;
                    @endphp

                    @if($perBagWeights && is_array($perBagWeights))
                        @foreach($perBagWeights as $weight)
                            @php
                                $bagCount++;
                                $totalWeight += floatval($weight);
                            @endphp
                            @if($bagCount % 10 == 1)
                                <p style="margin: 0 0 5px;">
                            @endif

                                <span>{{ number_format($weight, 1) }}</span>

                                @if($bagCount % 10 == 0 || $loop->last)
                                        @if($loop->last)
                                            = <span class="total">{{ number_format($totalWeight, 1) }}</span>
                                        @endif
                                    </p>
                                @endif
                        @endforeach
                    @else
                        <p style="margin: 0 0 5px;">
                            <span class="total">{{ number_format($order->total_weight, 1) }}</span>
                        </p>
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
                    Sub Total
                </td>
                <td class="right" style="background: #f3f3f3;">
                    {{ number_format($order->total_amount, 2) }}
                </td>
            </tr>
            <tr>
                <td style="border: 0;"></td>
                <td colspan="3" style="background: #f3f3f3;">Packaging Charge</td>
                <td class="right" style="background: #f3f3f3;">{{ number_format($order->packaging_charge, 2) }}</td>
            </tr>
            <tr>
                <td style="border: 0;"></td>
                <td colspan="3" style="background: #f3f3f3;">Hanali Charge</td>
                <td class="right" style="background: #f3f3f3;">{{ number_format($order->hamali_charge, 2) }}</td>
            </tr>
            <tr>
                <td style="border: 0;"></td>
                <td colspan="3" style="background: #f9c600; font-size: 14px; font-weight: 600;">Grand Total</td>
                <td class="right" style="background: #f9c600; font-size: 14px; font-weight: 600;">
                    <strong>{{ number_format($order->grand_amount, 2) }}</strong>
                </td>
            </tr>
            <!-- <tr>
        <td colspan="4" class="right"><strong>Total</strong></td>
        <td class="right"><strong>5385475.0</strong></td>
      </tr> -->
        </table>

        <table class="no-border" style="margin-top:20px;">
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