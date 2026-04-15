<x-cay-canh-layout>
    <x-slot name="title">
        Cây cảnh
    </x-slot>
<style>
        .cay-canh-name {
            font-weight: bold;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Hiển thị tối đa 2 dòng */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cay-canh-price {
            color: red;
            font-weight: bold;
            text-decoration: italic;
        }
</style>
<div class="list-cay-canh">
        @foreach($data as $caycanh)
        <div class="cay-canh">
            <a href="{{url('caycanh/'.$caycanh->id)}}">
                <img src="{{asset('storage/image/'.$caycanh->hinh_anh)}}" width="100%">
                <div style="margin-top: 10px;">
                    <p class="cay-canh-name">{{$caycanh->ten_san_pham}}</p>
                    <p class="cay-canh-price">{{number_format($caycanh->gia_ban)}}đ</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</x-cay-canh-layout>