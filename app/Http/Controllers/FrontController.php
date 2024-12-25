<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\FrontService;

class FrontController extends Controller
{
    //
    protected $frontService;

    public function __construct(FrontService $frontService)
    {
        $this->frontService = $frontService;
    }

    public function search(Request $request)
    {
        // Berikan nilai default jika 'keyword' tidak ada
        $keyword = $request->input('keyword', '');

        // Pastikan $keyword adalah string sebelum memanggil searchShoes
        $shoes = $this->frontService->searchShoes($keyword);

        return view('front.search', [
            'shoes' => $shoes,
            'keyword' => $keyword
        ]);
    }


    public function index()
    {
        $data = $this->frontService->getFrontPageData();
        return view('front.index', $data);
    }

    public function details (Shoe $shoe)
    {
        return view('front.details',compact('shoe'));
    }

    public function category(Category $category)
    {
        return view('front.category', compact('category'));
    }

}
