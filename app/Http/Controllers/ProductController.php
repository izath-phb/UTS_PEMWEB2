<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                        ->orWhere('description', 'like', '%' . $request->q . '%');
            })
            ->paginate(10);
       return view('dashboard.products.index',[
        'products' => $products,
        'q' => $request->q
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::get();
        return view('dashboard.products.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ], [
            'name.required' => 'Name harus diisi',
            'description.required' => 'Description harus diisi',
            'category_id.required' => 'Category harus diisi',
            'price.required' => 'Price harus diisi',
            'stock.required' => 'Stock harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(
                [
                    'errors'=>$validator->errors(),
                    'errorMessage'=>'Validasi Error, Silahkan lengkapi data terlebih dahulu'
                ]
            );
        }


        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
        ];

        Product::create($data);
        

        return redirect()->back()->with(['successMessage'=>'Data Berhasil Disimpan']);


    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $product = Product::find($id);
        $categories = Categories::get();

        return view('dashboard.products.edit',[
            'product'=>$product,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ], [
            'name.required' => 'Name harus diisi',
            'description.required' => 'Description harus diisi',
            'category_id.required' => 'Category harus diisi',
            'price.required' => 'Price harus diisi',
            'stock.required' => 'Stock harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(
                [
                    'errors'=>$validator->errors(),
                    'errorMessage'=>'Validasi Error, Silahkan lengkapi data terlebih dahulu'
                ]
            );
        }

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
        ];

        Product::where('id', $id)->update($data);
        

        return redirect()->back()->with(['successMessage'=>'Data Berhasil Disimpan']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $product = Product::find($id);

        $product->delete();

        return redirect()->back()
            ->with(
                [
                    'successMessage'=>'Data Berhasil Dihapus'
                ]
            );
    }
}
