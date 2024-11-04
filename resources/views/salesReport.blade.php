<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <style> 
        @page {
            size: A4 portrait;
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            justify-content: space-between;
        }
        .header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            align-items: center;
        }
        .header div:last-child {
            text-align: right;
        }
        .company-info {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }
        .title-section {
            display: flex;
            flex-direction: column; 
        }
        table {
            width: 100%;
            border: 1px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid darkgray;
        }
        td {
            font-size: 12px;
        }
        thead {
            text-align: center;
            background-color: #f2f2f2;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            text-align: center;
            font-size: 20px;
        }
        h5 {
            margin: 0;
            font-weight: normal;
        }
        p {
            margin: 0;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="title-section">
                <h1>{{$title}}</h1>
                <h4><b>Date Range:</b> From: {{ $fromDate ? $fromDate : 'N/A' }} To: {{ $toDate ? $toDate : 'N/A' }}</h4>
                <h4><b>Total Sales:</b> Php {{$total_sales}}</h4> <!-- Display total sales -->
                <h5>Report Date: {{$date}}</h5>
            </div>
            <div class="company-info">
                <h2>DavCom Consumer Goods Trading</h2>
                <p>Door 22, E.C. Business Center, <br> C.M. Recto Street, Brgy. 34-D, Poblacion District, <br> Davao City, Davao Del Sur Philippines 8000</p>
            </div>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Particulars</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>VAT</th> <!-- Add VAT column -->
                    <th>Discount</th>
                    <th>Amount Paid</th>
                    <th>Payment Method</th>
                    <th>Reference Number</th>
                    <th>Transaction Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderDetails as $orderItem)
                    <tr>
                        <td>{{ ucwords(strtolower($orderItem->customer_name)) }}</td>
                        <td>{{ $orderItem->product_name }}</td>
                        <td style="text-align:center">{{ $orderItem->qtyOrder }}</td>
                        <td>Php {{ number_format($orderItem->unitPrice, 2) }}</td>
                        <td>Php {{ number_format($orderItem->totalPrice, 2) }}</td> 
                        <td>Php {{ number_format($orderItem->VAT, 2) }}</td> 
                        <td>Php {{ number_format($orderItem->discount, 2) }}</td>
                        <td>Php {{ number_format($orderItem->amount_paid, 2) }}</td> 
                        <td>{{ ucwords(strtolower($orderItem->paymentType)) }}</td>
                        <td>{{ $orderItem->reference_num ?? 'N/A' }}</td>
                        <td>{{ $orderItem->order_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p><b>Validated by:</b> <u>{{$representative}}</u></p>
        </div>
    </div>
</body>
</html>
