<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSpecification;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    // LIST PAGE
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Product::latest();

            return DataTables::of($data)
                ->addIndexColumn()

                // ->addColumn('image', function ($row) {
                //     return '<img src="/uploads/' . $row->image . '" width="50">';
                // })

                ->addColumn('action', function ($row) {
                    return '
                        <a href="/products/' . $row->id . '/edit" class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">Delete</button>
                    ';
                })

                ->rawColumns([ 'action'])
                ->make(true);
        }

        return view('products.index', [
            'page_js' => 'datatable'
        ]);
    }

    // CREATE PAGE
    public function create()
    {
        return view('products.create', [
            'page_js' => 'validate'
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $data = $request->only('name', 'price', 'description');

        // IMAGE UPLOAD (v3)
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $name = time() . '.jpg';

            $path = public_path('uploads');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->scale(width: 800);
            $image->save($path . '/' . $name, quality: 60);

            $data['image'] = $name;
        }

        $product = Product::create($data);

        // SPECIFICATIONS
        if ($request->spec_key) {
            foreach ($request->spec_key as $i => $key) {
                if ($key) {
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'key' => $key,
                        'value' => $request->spec_value[$i] ?? ''
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Product Created Successfully']);
    }

    // EDIT PAGE
    public function edit($id)
    {
        $product = Product::with('specifications')->findOrFail($id);

        return view('products.edit', [
            'product' => $product,
            'page_js' => 'validate'
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        $data = $request->only('name', 'price', 'description');

        if ($request->hasFile('image')) {

            // delete old
            if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
                unlink(public_path('uploads/' . $product->image));
            }

            $file = $request->file('image');
            $name = time() . '.jpg';

            $path = public_path('uploads');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->scale(width: 800);
            $image->save($path . '/' . $name, quality: 60);

            $data['image'] = $name;
        }

        $product->update($data);

        // UPDATE SPEC
        ProductSpecification::where('product_id', $id)->delete();

        if ($request->spec_key) {
            foreach ($request->spec_key as $i => $key) {
                if ($key) {
                    ProductSpecification::create([
                        'product_id' => $id,
                        'key' => $key,
                        'value' => $request->spec_value[$i] ?? ''
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Product Updated Successfully']);
    }

    // DELETE
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
            unlink(public_path('uploads/' . $product->image));
        }

        $product->delete();

        return response()->json(['message' => 'Deleted']);
    }
}