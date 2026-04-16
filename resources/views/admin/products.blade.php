<x-cay-canh-layout title="Quản lý sản phẩm">
    <div class="text-center my-3">
        <h3 style="color: blue; font-weight: bold;">QUẢN LÝ SẢN PHẨM</h3>
    </div>

    <div class="mb-3 text-left">
        <a href="{{ route('admin.product.create') }}" class="btn btn-success">Thêm</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="product-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Tên khoa học</th>
                <th>Tên thông thường</th>
                <th>Độ khó</th>
                <th>Yêu cầu ánh sáng</th>
                <th>Nhu cầu nước</th>
                <th>Giá bán</th>
                <th>Ảnh</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->ten_san_pham }}</td>
                <td>{{ $p->ten_khoa_hoc ?? '---' }}</td>
                <td>{{ $p->ten_thong_thuong ?? '---' }}</td>
                <td>{{ $p->do_kho ?? '---' }}</td>
                <td>{{ $p->yeu_cau_anh_sang ?? '---' }}</td>
                <td>{{ $p->nhu_cau_nuoc ?? '---' }}</td>
                <td>{{ number_format($p->gia_ban, 0, ',', '.') }}đ</td>
                <td>
                    @if($p->hinh_anh)
                        <img src="{{ asset('storage/image/' . $p->hinh_anh) }}" width="50">
                    @else
                        <span>Chưa có ảnh</span>
                    @endif
                </td>
                <td style="white-space: nowrap;">
                    <a href="{{ url('/caycanh/' . $p->id) }}" class="btn btn-sm btn-primary">Xem</a>
                    <form action="{{ route('admin.product.destroy', $p->id) }}" method="POST" style="display:inline-block; margin-left: 5px;" onsubmit="return confirm('Xóa sản phẩm này?')">
                        @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#product-table').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tất cả"]],
                "order": [[0, 'asc']],
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entrie",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
        });
    </script>
</x-cay-canh-layout>