<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
   public function viewCart()
{
    $cart = session()->get('cart', []);
    $products = [];
    $total = 0;
    
    if(!empty($cart)) {
        $ids = implode(',', array_keys($cart));
        $products = DB::select("SELECT * FROM san_pham WHERE id IN ($ids) AND status = 1");
        
        foreach($products as $product) {
            $product->so_luong = $cart[$product->id];
            $total += $product->gia_ban * $product->so_luong;
        }
    }
    
    // Sửa đường dẫn view này
    return view('caycanh.index', compact('products', 'total'));
}
    
    // 2. Xóa sản phẩm khỏi giỏ
    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.view')->with('success', 'Đã xóa sản phẩm!');
    }
    
    // 3. Xử lý đặt hàng
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Giỏ hàng trống!');
        }
        
        try {
            DB::transaction(function () use ($cart, $request) {
                $orderId = DB::table('don_hang')->insertGetId([
                    'ngay_dat_hang' => DB::raw('NOW()'),
                    'hinh_thuc_thanh_toan' => $request->hinh_thuc_thanh_toan,
                    'user_id' => Auth::user()->id
                ]);
                
                foreach($cart as $id => $qty) {
                    $product = DB::select("SELECT gia_ban FROM san_pham WHERE id = ?", [$id])[0];
                    DB::table('chi_tiet_don_hang')->insert([
                        'ma_don_hang' => $orderId,
                        'id_san_pham' => $id,
                        'so_luong' => $qty,
                        'don_gia' => $product->gia_ban
                    ]);
                }
                
                session()->forget('cart');
            });
            
            // ===== THÊM PHẦN GỬI EMAIL =====
            $userEmail = Auth::user()->email;
            $userName = Auth::user()->name;
            
            Mail::raw("Cảm ơn $userName đã đặt hàng! Đơn hàng của bạn đã được ghi nhận thành công.", function ($message) use ($userEmail) {
                $message->to($userEmail)->subject('Xác nhận đặt hàng thành công');
            });
            // ===== KẾT THÚC PHẦN GỬI EMAIL =====
            
            return redirect()->route('cart.view')->with('success', 'Đặt hàng thành công! Email xác nhận đã được gửi.');
            
        } catch (\Exception $e) {
            return redirect()->route('cart.view')->with('error', 'Đặt hàng thất bại!');
        }
    }
    public function timKiem(Request $request)
    {
        $keyword = $request->keyword;
    
        $data = DB::select("
            SELECT * FROM san_pham 
            WHERE status = 1 
            AND (ten_san_pham LIKE ? OR mo_ta LIKE ?)
        ", ["%$keyword%", "%$keyword%"]);
    
        return view('caycanh.timkiem', compact('data', 'keyword'));
    }
}