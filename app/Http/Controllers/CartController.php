<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\OrderSuccessNotification;
use Illuminate\Support\Facades\Notification;

class CartController extends Controller
{
  
    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;

        if (!empty($cart)) {
            $ids = array_keys($cart);

            $products = DB::table('san_pham')
                ->whereIn('id', $ids)
                ->get();

            foreach ($products as $product) {
                $qty = $cart[$product->id]['so_luong'];
                $total += $product->gia_ban * $qty;
            }
        }

        return view('caycanh.cart-detail', compact('products', 'total'));
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
    
    public function index()
    {
        $cart = session()->get('cart', []);
        $categories = DanhMuc::all();
        $title = "Giỏ hàng của bạn";
        return view('caycanh.gio_hang', compact('cart', 'categories', 'title'));
    }

    public function add(Request $request, $id)
    {
        $sanPham = SanPham::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['so_luong'] += $request->so_luong;
        } else {
            $cart[$id] = [
                "ten_san_pham" => $sanPham->ten_san_pham,
                "so_luong" => $request->so_luong,
                "gia_ban" => $sanPham->gia_ban,
                "hinh_anh" => $sanPham->hinh_anh
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => count(session('cart'))
        ]);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    public function checkout(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart.view')->with('error', 'Giỏ hàng trống!');
    }

    DB::transaction(function () use ($cart, $request) {

        $orderId = DB::table('don_hang')->insertGetId([
        'ngay_dat_hang' => now(),
        'tinh_trang' => 1,
        'hinh_thuc_thanh_toan' => $request->hinh_thuc_thanh_toan,
        'user_id' => Auth::id()
        ]);

        foreach ($cart as $id => $item) {

            DB::table('chi_tiet_don_hang')->insert([
                'ma_don_hang' => $orderId,
                'id_san_pham' => $id,
                'so_luong' => $item['so_luong'],   // ✔ FIX Ở ĐÂY
                'don_gia' => $item['gia_ban']
            ]);
        }

        session()->forget('cart');
    });
    $user = Auth::user();

    if ($user) {
        Mail::raw(
            "Cảm ơn {$user->name} đã đặt hàng thành công! Đơn hàng của bạn đang được xử lý.",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Xác nhận đặt hàng thành công');
            }
        );

        return redirect()->route('cart.view')->with('success', 'Đặt hàng thành công!');
    } }
    public function ordercreate(Request $request)
        {
            $request->validate([
                "hinh_thuc_thanh_toan" => ["required", "numeric"]
            ]);
            $paymentLabels = [
                1 => 'Tiền mặt',
                2 => 'Chuyển khoản',
                3 => 'Thanh toán VNPay'
            ];
            $paymentMethod = $paymentLabels[$request->hinh_thuc_thanh_toan] ?? 'Khác';
            $data = [];
            $quantity = [];
            $emailData = [];
            if (session()->has('cart')) {
                $order = [
                    "ngay_dat_hang" => DB::raw("now()"),
                    "tinh_trang" => 1,
                    "hinh_thuc_thanh_toan" => $request->hinh_thuc_thanh_toan,
                    "user_id" => Auth::user()->id
                ];
                DB::transaction(function () use ($order, &$data, &$quantity, &$emailData) {
                    $id_don_hang = DB::table("don_hang")->insertGetId($order);
                    $cart = session("cart");
                    $list_book = "";
                    foreach ($cart as $id => $value) {
                        $quantity[$id] = $value;
                        $list_book .= $id . ", ";
                    }
                    $list_book = substr($list_book, 0, strlen($list_book) - 2);
                    $data = DB::table("san_pham")->whereRaw("id in (" . $list_book . ")")->get();
                    $detail = [];
                    foreach ($data as $row) {
                        $detail[] = [
                            "ma_don_hang" => $id_don_hang,
                            "id_san_pham" => $row->id,
                            "so_luong" => $quantity[$row->id],
                            "don_gia" => $row->gia_ban
                        ];
                    }
                    DB::table("chi_tiet_don_hang")->insert($detail);
                    foreach ($data as $row) {
                        $row->so_luong = $quantity[$row->id];
                        $emailData[] = $row;
                    }
                    session()->forget('cart');
                });

                if (!empty($emailData)) {
                    $user = User::find(Auth::id());
                    if ($user) {
                        $user->notify(new OrderDetailSend([
                            'items' => $emailData,
                            'customerName' => $user->name,
                            'paymentMethod' => $paymentMethod,
                        ]));
                    }
                }
                return redirect()->route('order')
                    ->with('success', 'Đơn đặt hàng của bạn thành công, vui lòng kiểm tra Email');
            }

            return redirect()->route('order');
        }
}