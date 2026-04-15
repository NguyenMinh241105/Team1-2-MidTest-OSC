<x-cay-canh-layout>
    <x-slot name="title">Giỏ hàng</x-slot>

    <div class="container mt-4">
        <h6 style="color: blue; margin-bottom: 5px; text-align: center;">DANH MỤC SẢN PHẨM</h6>

        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        @if(isset($products) && count($products) > 0)
            <table class="table table-bordered" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @php $stt = 1; @endphp
                    @foreach($products as $product)
                    <tr>
                        <td align="center">{{ $stt++ }}</td>
                        <td>{{ $product->ten_san_pham }}</td>
                        <td align="center">{{ $product->so_luong }}</td>
                        <td align="right">{{ number_format($product->gia_ban) }}đ</td>
                        <td align="center">
                            <form method="post" action="{{ route('cart.remove') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" align="right"><b>Tổng cộng:</b></td>
                        <td colspan="2"><b>{{ number_format($total) }}đ</b></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="text-center mt-3">
                <form method="post" action="{{ route('cart.checkout') }}">
                    @csrf
                    <select name="hinh_thuc_thanh_toan" class="form-control w-25 d-inline-block">
                        <option value="1">Tiền mặt</option>
                        <option value="2">Chuyển khoản</option>
                    </select>
                    <button type="submit" class="btn btn-success">ĐẶT HÀNG</button>
                </form>
            </div>
        @else
            <div class="alert alert-info text-center">
                Giỏ hàng trống!
            </div>
        @endif
    </div>
</x-cay-canh-layout>