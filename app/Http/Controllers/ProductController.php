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
        $products = Product::with('category') // Eager load relasi category
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            })
            ->paginate(10);

        return view('dashboard.products.index', [
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
            'slug' => 'required|string|max:255|unique:products,slug',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'required',
            'category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', // jika Anda ingin menyimpan gambar juga
        ], [
            'name.required' => 'Name harus diisi',
            'slug.required' => 'Slug harus diisi',
            'sku.unique' => 'SKU sudah digunakan',
            'description.required' => 'Description harus diisi',
            'category_id.required' => 'Category harus diisi',
            'price.required' => 'Price harus diisi',
            'stock.required' => 'Stock harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with([
                'errors' => $validator->errors(),
                'errorMessage' => 'Validasi Error, Silahkan lengkapi data terlebih dahulu'
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'sku' => $request->input('sku'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
        ];

        // Jika ingin menyimpan gambar (opsional)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploads/products', $imageName, 'public');
            $data['image'] = $imagePath; // tambahkan ke $data
        }

        Product::create($data);

        return redirect()->back()->with(['successMessage' => 'Data Berhasil Disimpan']);
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

        return view('dashboard.products.edit', [
            'product' => $product,
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
            'slug' => 'required|string|max:255|unique:products,slug',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'required',
            'category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|max:2048', // jika Anda ingin menyimpan gambar juga
        ], [
            'name.required' => 'Name harus diisi',
            'slug.required' => 'Slug harus diisi',
            'sku.unique' => 'SKU sudah digunakan',
            'description.required' => 'Description harus diisi',
            'category_id.required' => 'Category harus diisi',
            'price.required' => 'Price harus diisi',
            'stock.required' => 'Stock harus diisi',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(
                [
                    'errors' => $validator->errors(),
                    'errorMessage' => 'Validasi Error, Silahkan lengkapi data terlebih dahulu'
                ]
            );
        }

        $data = [
            'name' => $request->input('name'),
            'sku' => $request->input('sku'),
            'slug' => $request->input('slug'),
            'image' => $request->input('image'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploads/products', $imageName, 'public');
            $data['image'] = $imagePath; // tambahkan ke $data
        }

        Product::where('id', $id)->update($data);


        return redirect()->back()->with(['successMessage' => 'Data Berhasil Disimpan']);
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
                    'successMessage' => 'Data Berhasil Dihapus'
                ]
            );
    }
}
