<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Estimate Bill</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: "Arial", sans-serif;
            margin: 0;
            background: #f9f9f9;
            color: #222;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px 30px;
            border: 2px solid #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            position: relative;
        }

        .header img {
            width: 60px;
            height: auto;
            position: absolute;
            left: 20px;
            top: 5px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .header h2 {
            font-size: 24px;
            margin: 5px 0 0;
            font-weight: bold;
            color: #d35400;
        }

        /* Meta info */
        .meta {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 15px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 13px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
        }

        table th {
            background: #f1f1f1;
            font-weight: bold;
        }

        .particulars {
            text-align: left;
            font-family: "Courier New", monospace;
            white-space: pre-wrap;
        }

        /* Totals */
        .totals-box {
            border: 2px solid #000;
            max-width: 350px;
            margin-left: auto;
            margin-top: 15px;
            font-size: 14px;
        }

        .totals-box .row {
            display: flex;
            justify-content: space-between;
            padding: 6px 10px;
            border-bottom: 1px solid #ccc;
        }

        .totals-box .row:last-child {
            border-bottom: none;
        }

        .totals-box .label {
            font-weight: bold;
        }

        .totals-box .grand {
            background: #f1c40f;
            font-size: 16px;
            font-weight: bold;
            color: #000;
            border-top: 2px solid #000;
        }

        /* Footer */
        .footer {
            border-top: 2px solid #000;
            margin-top: 30px;
            padding-top: 10px;
            text-align: center;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="ganesha.png" alt="Logo">
            <h1>श्री गणेशाय नमः</h1>
            <h2>ESTIMATE</h2>
        </div>

        <!-- Meta info -->
        <div class="meta">
            <div>
                <strong>SR No:</strong> 632<br>
                <strong>To:</strong> D DA
            </div>
            <div>
                <strong>Date:</strong> 28/08/2025<br>
                <strong>Branch:</strong> SELF
            </div>
        </div>

        <div class="meta">
            <div>
                <strong class="margin-bottom:10px">A/C:</strong> ----<br>
                <strong>Garage:</strong> ----
            </div>
            <div><strong>LR No:</strong> ----</div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th style="width:50%;">Particulars</th>
                    <th>Bag</th>
                    <th>Net Wt.</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="particulars">
                        SUPARI FALI<br>
                        50.6 49.3 50.1 49.6 49.5 50.0<br>
                        50.3 49.4 50.1 50.1<br>
                        50.3 50.3 49.5 49.1 50.7 50.3<br>
                        49.9 50.1 50.1<br>
                        49.1 49.7 50.0 50.0 50.6 50.5<br>
                        50.0 50.0 49.8 50.5<br>
                        49.2 50.3 50.4 49.7 49.2 50.3<br>
                        50.3 49.9 50.4 49.9<br>
                        50.0 50.2 49.4 50.6 49.3 49.3<br>
                        50.3 49.0 50.6 49.2 -10.0=2487
                    </td>
                    <td>50</td>
                    <td>2487.0</td>
                    <td>310.00</td>
                    <td>770970.00</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-box">
            <div class="row"><span class="label">Sub Total:</span> <span>₹ 5,385,475.00</span></div>
            <div class="row"><span class="label">Packaging Charge:</span> <span>₹ 2,000.00</span></div>
            <div class="row"><span class="label">Hanali Charge:</span> <span>₹ 1,000.00</span></div>
            <div class="row grand"><span class="label">Grand Total:</span> <span>₹ 5,388,475.00</span></div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Payment condition within 3 days<br>
            This is a computer-generated estimate
        </div>
    </div>
</body>

</html>