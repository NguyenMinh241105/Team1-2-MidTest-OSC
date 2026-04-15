<x-cay-canh-layout>
    <x-slot name="title">Tìm kiếm: {{ $keyword }}</x-slot>

    <div class="container">

        <h3 class="text-center text-primary"></h3>

        <!-- FILTER GIỐNG GIAO DIỆN -->
        <div class="mb-3 text-center">
            <span>Tìm kiếm theo:</span>

            <a href="{{ url('/timkiem?keyword='.$keyword.'&sort=asc') }}" class="btn btn-outline-secondary btn-sm">
                Giá tăng dần
            </a>

            <a href="{{ url('/timkiem?keyword='.$keyword.'&sort=desc') }}" class="btn btn-outline-secondary btn-sm">
                Giá giảm dần
            </a>

            <a href="{{ url('/timkiem?keyword='.$keyword.'&filter=easy') }}" class="btn btn-outline-secondary btn-sm">
                Dễ chăm sóc
            </a>

            <a href="{{ url('/timkiem?keyword='.$keyword.'&filter=shade') }}" class="btn btn-outline-secondary btn-sm">
                Chịu được bóng râm
            </a>
        </div>

        @if($data->count() > 0)
            <div class="list-cay-canh">
                @foreach($data as $row)
               <div style="border: 1px solid #ddd; border-radius: 8px; padding: 10px; margin: 10px; text-align: center;">
                <img src="{{ asset('storage/image/' . $row->hinh_anh) }}" width="120"><br>
                    <b>{{ $row->ten_san_pham }}</b><br>
                 <span style="color: red; font-style: italic;">Giá: {{ number_format($row->gia_ban) }}đ</span>
            </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning text-center">
                Không tìm thấy sản phẩm nào
            </div>
        @endif

    </div>
</x-cay-canh-layout>