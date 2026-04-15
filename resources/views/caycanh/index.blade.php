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
    <div class="filter-buttons mb-2 mt-4">
        Sắp xếp theo:

        {{-- Nhóm sắp xếp giá: Giữ lại các lọc easy_care và shade_tolerant hiện có --}}
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"
            class="btn {{ request('sort') == 'price_asc' ? 'btn-secondary' : 'btn-outline-secondary' }}">
            Giá tăng dần
        </a>

        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}"
            class="btn {{ request('sort') == 'price_desc' ? 'btn-secondary' : 'btn-outline-secondary' }}">
            Giá giảm dần
        </a>

        {{-- Nhóm lọc đặc tính: Giữ lại tham số sort hiện có --}}
        <a href="{{ request()->fullUrlWithQuery(['easy_care' => 1]) }}"
            class="btn {{ request('easy_care') == 1 ? 'btn-success' : 'btn-outline-secondary' }}">
            Dễ chăm sóc
        </a>

        <a href="{{ request()->fullUrlWithQuery(['shade_tolerant' => 1]) }}"
            class="btn {{ request('shade_tolerant') == 1 ? 'btn-success' : 'btn-outline-secondary' }}">
            Chịu được bóng râm
        </a>

        {{-- Nút xóa nhanh các bộ lọc bằng cách quay về URL hiện tại không kèm tham số --}}
        <a href="{{ url()->current() }}" class="card-link btn btn-link text-decoration-none">Thiết lập lại</a>
    </div>
    <div class="list-cay-canh">
        @foreach($products as $caycanh)
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