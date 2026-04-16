<x-cay-canh-layout title="Thêm sản phẩm">
    <div class="text-center my-3">
        <h5 style="color: blue; font-weight: bold;">THÊM</h5>
    </div>

    <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Tên sản phẩm</label>
            <input type="text" name="ten_san_pham" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Tên khoa học</label>
            <input type="text" name="ten_khoa_hoc" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Tên thông thường</label>
            <input type="text" name="ten_thong_thuong" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả</label>
            <textarea name="mo_ta" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Độ khó</label>
            <select name="do_kho" class="form-control">
                <option value="">-- Chọn --</option>
                <option value="Dễ chăm sóc">Dễ chăm sóc</option>
                <option value="Trung bình">Trung bình</option>
                <option value="Khó">Khó</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Yêu cầu ánh sáng</label>
            <input type="text" name="yeu_cau_anh_sang" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Nhu cầu nước</label>
            <input type="text" name="nhu_cau_nuoc" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Giá bán</label>
            <input type="number" name="gia_ban" class="form-control" step="1000" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Ảnh</label>
            <input type="file" name="hinh_anh" class="form-control" accept="image/*">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-sm" style="font-weight: bold;">Lưu</button>
        </div>
    </form>
</x-cay-canh-layout>