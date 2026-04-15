<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function adminIndex()
    {
        $products = SanPham::where('status', 1)->get();
        return view('admin.products', compact('products'));
    }

    public function destroy($id)
    {
        $product = SanPham::findOrFail($id);
        $product->status = 0;
        $product->save();
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công!');
    }

    public function show($id)
    {
        $product = SanPham::where('id', $id)->where('status', 1)->firstOrFail();
        return view('product-detail', compact('product'));
    }

    public function create()
    {
        return view('admin.product-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_san_pham' => 'required|string|max:255',
            'gia_ban' => 'required|numeric',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('_token', 'hinh_anh');

        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/image', $fileName);
            $data['hinh_anh'] = $fileName;
        }

        $data['status'] = 1;
        SanPham::create($data);

        return redirect()->route('admin.products')->with('success', 'Thêm sản phẩm thành công!');
    }
}