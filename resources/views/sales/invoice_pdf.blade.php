<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> --}}
    <style>
        body {
            font-family: "THSarabunNew";
            line-height: 1.2; /* ปรับให้ข้อความชิดกันมากขึ้น */
        }
        .invoice-header {
        font-size: 40px; /* ลดขนาดฟอนต์ */
        line-height: 1; /* ลดระยะห่างระหว่างบรรทัด */
        margin-top: -40px; /* ค่า -20px ทำให้ชิดขอบบนมากขึ้น */
        float: right;
        display: flex;
        }
        left {
float: left;
line-height: 0.3em;
}
right {
float: right;
line-height: 0.3em;
}
underright {
float: right;
line-height: 0.3em;
}
        .table {
        width: 100%;
        border-collapse: collapse; /* ปิดการแยกเส้นขอบ */
         margin-top: 100px;
        }
        th{
          
            line-height: 0.3;
           
        }
        th, td {
            padding: 10px 10px; /* ลด padding ให้แคบลง */
            text-align: left; /* จัดข้อความให้ชิดซ้าย */
            
            line-height: 0.3;
        }
        td {
            
            padding: 10px 10px;
            text-align: left;
            border: none;
            line-height: 0.3;
        }
        tbody{
            border-bottom: 1px solid #000;
        }
        thead{
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .table th {
            font-weight: bold; /* ทำให้ข้อความในหัวตารางหนา */
            border-right:none;
        }
        .table, td {
       
           border: none;
    }
 
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        บิลขาย
    </div>
 
    <div>

        <left>
            <p>รหัสลูกค้า: {{ $sale_pdf->customer_id }}</p>
            <p>ชื่อลูกค้า: {{ $sale_pdf->customer_name }}</p>
            <p>ที่อยู่: {{ $sale_pdf->address }}</p>
            <p>โทร: {{ $sale_pdf->phone }}</p>
        </left>
        
        <right>
            <p>เลขที่เอกสาร: {{ $sale_pdf->invoice_number }}</p>
            <p>วัรที่เอกสาร: {{ $sale_pdf->created_at }}</p>
            <p>การชำระ: {{ $sale_pdf->payment_method == 'cash' ? 'เงินสด' : 'เครดิด' }}</p>
            <p>กำหนดชำระ:-</p>
        </right>
        
        </div>
    <table class="table">
        <thead>
            <tr>
                <th>ลำดับที่</th>
                <th>รายการ</th>
                <th>จำนวน</th>
                <th>หน่วย</th>
                <th>ราคา</th>
                <th>ส่วนลด</th>
                <th>เป็นเงิน</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $productSale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $productSale->name }}</td> 
                    <td>{{ $productSale->quantity }}</td>
                    <td>{{ $productSale->unit }}</td>
                    <td>{{ $productSale->price}}</td>
                    <td>-</td>
                    <td>{{ number_format($productSale->quantity * $productSale->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <right>
    <p>รวมราคา {{ number_format($sale_pdf->total, 2) }}    บาท </p>
    <p>ส่วนลด&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; บาท   </p>
    <p>รวมสุทธิ {{ number_format($sale_pdf->total, 2) }} &nbsp;   บาท   </p>
    <hr>
    </right>
    <p style="margin-top: 15%; margin-left:10%" >
        ลงชื่อ.........................................ผู้รับสินค้า  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ลงชื่อ.........................................ผู้รับเงิน <br>
       
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  วันที่ ......../........../........... 
       
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       
        วันที่ ......../........../...........
    </p>
    {{-- <p ><strong>ยอดรวม: </strong>{{ number_format($sale_pdf->total, 2) }} บาท</p> --}}

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
