<html>

<head>
    <style>
        .book-table {
            border-collapse: collapse;
        }

        .book-table tr th {
            text-align: center;
        }

        .book-table tr th,
        .book-table tr td {
            border: 1px solid #000;
            padding: 3px;
        }
    </style>
</head>

<body>
    <div style='text-align:center;font-weight:bold;color:#15c;'>
        THÔNG TIN ĐƠN HÀNG
    </div>
    <div style='margin:10px 0; text-align:center;'>
        <div><strong>Khách hàng:</strong> {{ $customerName }}</div>
        <div><strong>Hình thức thanh toán:</strong> {{ $paymentMethod }}</div>
    </div>

    <table class='book-table' style='margin:0 auto; width:70%'>
        <thead>
            <th>STT</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
        </thead>
        <tbody>
            @php $tongTien = 0; @endphp
            @foreach($items as $key => $row)
            <tr>
                {{-- Vì là Object (stdClass), ta dùng dấu -> --}}
                <td align='center'>{{ $loop->iteration }}</td>
                <td>{{ $row->ten_san_pham }}</td>
                <td align='center'>{{ $row->so_luong }}</td>
                <td align='center'>{{ number_format($row->gia_ban, 0, ',', '.') }}đ</td>
            </tr>
            @php
            // Ép kiểu chắc chắn về số để thực hiện phép nhân
            $qty = (int) $row->so_luong;
            $price = (float) $row->gia_ban;
            $tongTien += $qty * $price;
            @endphp
            @endforeach

            {{-- Hiển thị tổng tiền cuối bảng --}}
            <tr>
                <td colspan="3" align="right"><strong>Tổng cộng:</strong></td>
                <td align="center"><strong>{{ number_format($tongTien, 0, ',', '.') }}đ</strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>