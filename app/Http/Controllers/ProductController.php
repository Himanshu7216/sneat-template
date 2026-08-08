<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    // =========================================== category ==========================================

    public function getCategory(Request $request)
    {

        if ($request->ajax()) {

            $data = Category::query();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {

                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">
                            Edit
                        </button>

                        <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">
                            Delete
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('product_management.category');
    }

    public function createCategory()
    {
        return view('product_management.createCategory');
    }
    public function storeCategory(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'categoryname' => ['required', 'string', 'min:5', 'max:50', 'regex:/^[a-zA-Z0-9\s]+$/', 'unique:categories,name'],
            'description' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'in:active,inactive']
        ]);


        $category = Category::create([
            'name' => $request->categoryname ?? '',
            'description' => $request->description ?? '',
            'status' => $request->status ?? 'active'
        ]);


        if (isset($category)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category Added successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category add Failed'
            ]);
        }
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);

        return view('product_management.editCategory', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        try {

            $category = Category::findOrFail($id);

            $validated = $request->validate([
                'categoryname' => [
                    'required',
                    'string',
                    'min:5',
                    'max:50',
                    'regex:/^[a-zA-Z0-9\s]+$/',
                    'unique:categories,name,' . $category->id
                ],
                'description' => [
                    'required',
                    'string',
                    'max:1000'
                ],
                'status' => [
                    'required',
                    'in:active,inactive'
                ]
            ]);

            $category->update([
                'name' => $request->categoryname,
                'description' => $request->description,
                'status' => $request->status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Category update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteCategory($id)
    {
        try {

            $category = Category::findOrFail($id);

            $category->delete();
            // Because model uses "SoftDeletes", $category->delete() does not permanently delete the category.

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Category delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================== products ==========================================
    public function getproducts(Request $request)
    {
        if ($request->ajax()) {

            $data = Products::with('category')->select('products.*');

            return DataTables::of($data)
                ->addColumn('category', function ($row) {
                    return $row->category
                        ? $row->category->name
                        : 'No Category';
                })
                ->addColumn('action', function ($row) {

                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">
                            Edit
                        </button>

                        <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">
                            Delete
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('product_management.products');
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('product_management.create', ['categories' => $categories]);
    }
    public function storeProduct(ProductRequest $request)
    {
        try {

            $category = Category::where('id', $request->category)
                ->where('status', 'active')
                ->first();

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => ['category' => ['Selected category is invalid or inactive.']]
                ], 422);
            }

            $products = [
                'sku'         => $request->sku,
                'name'        => $request->productname,
                'description' => $request->description,
                'color'       => $request->color,
                'size'        => $request->size,
                'price'       => $request->price,
                'category_id' => $request->category,
            ];

            // Images are pre-uploaded by Dropzone to public/uploads/products/.
            // The frontend sends the filenames back as uploaded_images[].
            $images = $request->input('uploaded_images', []);
            $products['image'] = array_values(array_filter($images));

            Products::create($products);

            return response()->json([
                'status'  => 'success',
                'message' => 'Product created successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong while creating the product.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function editProduct($id)
    {
        $product = Products::findOrFail($id);

        // Get all active categories
        $categories = Category::where('status', 'active')->get();
        return view('product_management.editProduct', [
            'product' => $product,
            'categories' => $categories
        ]);
    }
    public function updateProduct(Request $request, $id)
    {
        try {

            $product = Products::findOrFail($id);

            // Validation
            $validator = Validator::make($request->all(), [
                'sku' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9-_]+$/', 'unique:products,sku,' . $product->id],
                'productname' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
                'description' => ['required', 'string', 'max:1000'],
                'color' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\s]+$/'],
                'size' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9\s.-]+$/'],
                'price' => ['required', 'numeric', 'min:0'],
                'category' => ['required', 'exists:categories,id', 'integer'],
                'uploaded_images' => ['required', 'array', 'min:1'],
                'uploaded_images.*' => ['string'],
            ]);

            // Return validation errors
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $category = Category::where('id', $request->category)
                ->where('status', 'active')
                ->first();

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => ['category' => ['Selected category is invalid or inactive.']]
                ], 422);
            }

            $oldImages = is_array($product->image) ? array_values(array_filter($product->image)) : [];
            $rawUploadedImages = array_values(array_unique(array_filter($request->input('uploaded_images', []))));

            $newImages = [];
            $seenHashes = [];

            foreach ($rawUploadedImages as $imgName) {
                $imgPath = public_path('uploads/products/' . $imgName);
                if (!is_file($imgPath)) {
                    $altPath = storage_path('app/public/Products/' . $imgName);
                    if (is_file($altPath)) {
                        $imgPath = $altPath;
                    }
                }

                if (is_file($imgPath)) {
                    $hash = md5_file($imgPath);
                    if ($hash && in_array($hash, $seenHashes, true)) {
                        // Duplicate image content detected — if this is a newly uploaded duplicate file, remove it from disk
                        if (!in_array($imgName, $oldImages, true)) {
                            @unlink($imgPath);
                        }
                        // Skip storing duplicate image in table
                        continue;
                    }
                    if ($hash) {
                        $seenHashes[] = $hash;
                    }
                }

                if (!in_array($imgName, $newImages, true)) {
                    $newImages[] = $imgName;
                }
            }

            $removedImages = array_diff($oldImages, $newImages);
            foreach ($removedImages as $removedImage) {
                $removedPath = public_path('uploads/products/' . $removedImage);
                if (is_file($removedPath)) {
                    @unlink($removedPath);
                }
            }

            $productData = [
                'sku' => $request->sku,
                'name' => $request->productname,
                'description' => $request->description,
                'color' => $request->color,
                'size' => $request->size,
                'price' => $request->price,
                'category_id' => $request->category,
                'image' => $newImages,
            ];

            $product->update($productData);

            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while updating the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteProduct($id)
    {
        try {

            $product = Products::findOrFail($id);

            $product->delete();
            // Because model uses "SoftDeletes", $product->delete() does not permanently delete the category.

            return response()->json([
                'status' => 'success',
                'message' => 'product deleted successfully'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'product delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:3072',
        ]);

        $file = $request->file('image');

        $name = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('uploads/products'), $name);

        return response()->json([
            'success' => true,
            'filename' => $name,
            'path' => asset('uploads/products/' . $name)
        ]);
    }
}
