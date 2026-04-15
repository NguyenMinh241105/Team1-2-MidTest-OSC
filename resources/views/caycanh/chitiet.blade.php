<x-cay-canh-layout>
    <x-slot name="title">
        Chi tiết cây cảnh
    </x-slot>
    <style>
    .cay-canh-text {
        margin-left: 50px;
    }

    .cay-canh-text h2 {
        margin-top: 0;
        font-size: 30px;
    }

    .cay-canh-text p {
        font-size: 15px;
        padding: 0;
        margin: 0;
    }

    .cay-canh-info {
        margin-top: 20px;
    }
</style>
    <div class="cay-canh-info">
        <img src="{{asset('storage/image/'.$data->hinh_anh)}}" width="300px" style="float:left; margin-right:20px;">
        <div class="cay-canh-text">
            <h2>{{$data->ten_san_pham}}</h2>
            <p>Tên khoa học: {{$data->ten_khoa_hoc}}</p>
            <p>Tên thông thường: {{$data->ten_thong_thuong}}</p>
            <p>Mô tả: {{$data->mo_ta}}</p>
            <p>Quy cách sản phẩm: {{$data->quy_cach_san_pham}}</p>
            <p>Độ khó: {{$data->do_kho}}</p>
            <p>Yêu cầu ánh sáng: {{$data->yeu_cau_anh_sang}}</p>
            <p>Nhu cầu nước: {{$data->nhu_cau_nuoc}}</p>
            <p>Giá: <strong style="color:red">{{number_format($data->gia_ban,0,".",",")}} VNĐ</strong></p>
           <form action="{{ route('cart.add') }}" method="POST" class="cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $data->id }}">
                    <label for="so_luong">Số lượng mua:</label>
                    <input type="number" name="so_luong" id="so_luong" value="1" min="1" class="qty-input"> <br>
                    <button type="submit" class="btn-add-cart btn btn-primary">Thêm vào giỏ hàng</button>
                </form>

            
        </div>
    </div>
</x-cay-canh-layout>