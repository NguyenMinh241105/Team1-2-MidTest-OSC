<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index(Request $request, $id_danh_muc = null)
    {
        // 1. Lấy danh sách danh mục để hiển thị trên menu (Dùng cho layout)
        $categories = DB::table('danh_muc')->get();

        // 2. Khởi tạo truy vấn bảng sản phẩm (status = 1 là chưa xóa)
        $query = DB::table('san_pham')->where('status', 1);

        // 3. Lọc theo danh mục (Join bảng trung gian theo file SQL)
        if ($id_danh_muc) {
            $query->join('sanpham_danhmuc', 'san_pham.id', '=', 'sanpham_danhmuc.id_san_pham')
                ->where('sanpham_danhmuc.id_danh_muc', $id_danh_muc)
                ->select('san_pham.*');
        }

        // 4. Tìm kiếm (Yêu cầu 5)
        if ($request->has('keyword')) {
            $query->where('ten_san_pham', 'like', '%' . $request->keyword . '%');
        }

        // 5. Lọc đặc tính: Dễ chăm sóc
        if ($request->query('easy_care') == 1) {
            $query->where('do_kho', 'Dễ chăm sóc');
        }

        // 6. Lọc đặc tính: Chịu bóng râm
        if ($request->query('shade_tolerant') == 1) {
            $query->where('yeu_cau_anh_sang', 'like', '%bóng râm%');
        }

        // 7. Sắp xếp giá
        if ($request->has('sort')) {
            $direction = ($request->sort == 'price_asc') ? 'asc' : 'desc';
            $query->orderBy('gia_ban', $direction);
        }

        // 8. Thực thi lấy dữ liệu
        // Mặc định trang chủ lấy 20 sản phẩm, nếu có lọc thì lấy theo kết quả lọc
        if (!$id_danh_muc && !$request->hasAny(['keyword', 'easy_care', 'shade_tolerant', 'sort'])) {
            $products = $query->limit(20)->get();
        } else {
            $products = $query->get();
        }

        // Trả về view trong thư mục resources/views/caycanh/index.blade.php
        return view('caycanh.index', compact('products', 'categories'));
    }

    public function timkiem(Request $request)
    {
        $keyword = $request->input('keyword');
        $data = DB::select("SELECT * FROM `san_pham` WHERE ten_san_pham like ?", ["%" . $keyword . "%"]);
        return view("caycanh.search-results", compact("data"));
    }
    public function chitietsanpham($id)
    {
        $data = DB::selectOne("SELECT * FROM `san_pham` WHERE id = ?", [$id]);
        return view("caycanh.chitiet", compact("data"));
    }
     public function addToCart(Request $request)
    {
        $id = $request->input('product_id');
        $quantity = $request->input('so_luong', 1);

        $sanPham = DB::table('san_pham')->where('id', $id)->first();
        if(!$sanPham) abort(404);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['so_luong'] += $quantity;
        } else {
            $cart[$id] = [
                "ten_san_pham" => $sanPham->ten_san_pham,
                "so_luong" => $quantity,
                "gia_ban" => $sanPham->gia_ban,
                "hinh_anh" => $sanPham->hinh_anh
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }
}
