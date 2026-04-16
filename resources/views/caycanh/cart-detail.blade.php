

<x-cay-canh-layout>
    <x-slot name="title">Giỏ hàng</x-slot>

    <div class="container mt-4">
        <h4 style="color: blue; text-align: center;">GIỎ HÀNG CỦA BẠN</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        @php
            $cart = session()->get('cart', []);
        @endphp
        

        @auth
                        @if(count($cart)>0)
            <table class="table table-bordered">
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
                    @php $stt = 1; $total = 0; @endphp
                    @foreach($cart as $id => $item)
                    @php $thanhTien = $item['so_luong'] * $item['gia_ban']; $total += $thanhTien; @endphp
                    <tr>
                        <td align="center">{{ $stt++ }}</td>
                        <td>{{ $item['ten_san_pham'] }}</td>
                        <td align="center">{{ $item['so_luong'] }}</td>
                        <td align="right">{{ number_format($item['gia_ban']) }}đ</td>
                        <td align="center">
                            <form method="post" action="{{ route('cart.remove') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
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
                <form method="post" action="{{ route('ordercreate') }}">
                    @csrf
                    <select name="hinh_thuc_thanh_toan" class="form-control w-25 d-inline-block">
                        <option value="1">Tiền mặt</option>
                        <option value="2">Chuyển khoản</option>
                        <option value="3">Thanh toán VN Pay</option>
                    </select>
                    <button type="submit" class="btn btn-success">ĐẶT HÀNG</button>
                </form>
            </div>
        @else
            <div class="alert alert-info text-center">
                Giỏ hàng trống! <a href="{{ url('/') }}">Tiếp tục mua hàng</a>
            </div>
        @endif
        
        @else
            <div class="alert alert-warning text-center">
                Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để xem giỏ hàng!
            </div>
        @endauth
    </div>
</x-cay-canh-layout>