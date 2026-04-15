<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CayCanhController extends Controller
{
   public function timKiem(Request $request)
{
    $keyword = trim($request->keyword);
    $sort = $request->sort;
    $filter = $request->filter;

    // ❗ chưa nhập từ khóa
    if (empty($keyword)) {
        return view('caycanh.timkiem', [
            'data' => collect(), // rỗng
            'keyword' => '',
            'message' => 'Vui lòng nhập từ khóa!'
        ]);
    }

    $query = DB::table('san_pham')
        ->where(function ($q) use ($keyword) {
            $q->where('ten_san_pham', 'like', "%$keyword%")
              ->orWhere('mo_ta', 'like', "%$keyword%");
        });

    // sort
    if ($sort == 'asc') {
        $query->orderBy('gia_ban', 'asc');
    } elseif ($sort == 'desc') {
        $query->orderBy('gia_ban', 'desc');
    }

    // filter
    if ($filter == 'easy') {
        $query->where('de_cham_soc', 1);
    }
    if ($filter == 'shade') {
        $query->where('chiu_bong_ram', 1);
    }

    $data = $query->get();

    return view('caycanh.timkiem', [
        'data' => $data,
        'keyword' => $keyword,
        'message' => null
    ]);
}
}