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
        td{
            font-size: 12px;
        }
        thead {
            text-align: center;
            background-color: #f2f2f2;
        }
        h5{
            margin: 0;
            font-weight: normal;
        }
        p{
            margin: 0;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="title-selection">
                <h1>{{ $title }}</h1>
                <p>Report Date: {{ $date }}</p>
                <p>Date Range: {{ $fromDate }} to {{ $toDate }}</p>
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
                    <th class="w-40">Category Name</th>
                    <th class="w-40">Serial Number</th>
                    <th class="w-40">Brand Name</th>
                    <th class="w-40">Model Name</th>
                    <th class="w-40">Supplier Name</th>
                    <th class="w-40">Description</th>
                    <th class="w-40">Current Stocks</th>
                    <th class="w-40">Price</th>
                    <th class="w-40">Date Added</th>
                    <th class="w-40">Warranty Expired</th>
                    <th class="w-40">Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->categoryName }}</td>
                    <td>{{ $product->serialNum }}</td>
                    <td>{{ $product->brandName }}</td> 
                    <td>{{ $product->modelName }}</td>
                    <td>{{ $product->supplierName }}</td>
                    <td>{{ $product->product_desc }}</td>
                    <td>{{ $product->stock_qty }}</td>
                    <td>{{ $product->unit_price }}</td>
                    <td>{{ $product->prod_add }}</td>
                    <td>{{ $product->warranty }}</td>
                    <td>{{ $product->unit }}</td> 
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer" style="display: flex; justify-content: space-between; align-items: center;">
            <p style="margin: 0; text-align: right;"><b>Validated by:</b> <u>{{ $representative }}</u></p> 
        </div>
    </div>
</body>
</html>
