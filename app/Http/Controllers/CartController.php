<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Notifications\OrderDetailSend;
use App\Notifications\OrderSuccessNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;

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

        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.view')->with('success', 'Đã xóa sản phẩm!');
    }


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

        if (session()->has('cart')) {
            $cart = session("cart");
            $emailData = [];

            $order = [
                "ngay_dat_hang" => now(),
                "tinh_trang" => 1,
                "hinh_thuc_thanh_toan" => $request->hinh_thuc_thanh_toan,
                "user_id" => Auth::id()
            ];

            DB::transaction(function () use ($order, $cart, &$emailData) {
                $id_don_hang = DB::table("don_hang")->insertGetId($order);

                $productIds = array_keys($cart);
                $products = DB::table("san_pham")->whereIn("id", $productIds)->get();

                $detail = [];
                foreach ($products as $product) {
                    $quantity = $cart[$product->id]['so_luong'];

                    $detail[] = [
                        "ma_don_hang" => $id_don_hang,
                        "id_san_pham" => $product->id,
                        "so_luong" => $quantity,
                        "don_gia" => $product->gia_ban
                    ];

                    $product->so_luong = $quantity;
                    $emailData[] = $product;
                }

                DB::table("chi_tiet_don_hang")->insert($detail);

                session()->forget('cart');
            });

            if (!empty($emailData)) {
                $user = Auth::user();
                $user->notify(new OrderDetailSend([
                    'items' => $emailData,
                    'customerName' => $user->name,
                    'paymentMethod' => $paymentMethod,
                ]));
            }

            return redirect()->route('cart.view')
                ->with('success', 'Đơn đặt hàng của bạn thành công, vui lòng kiểm tra Email');
        }

        return redirect()->route('cart.view');
    }
}
