<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Import Product model
use App\Http\Helpers\ResponseHelpers;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Only authenticated users can access these methods
    }

    // Create a new product
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:1',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return ResponseHelpers::sendError('Validation Error', $validator->errors(), 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image_url' => $request->image_url,
            'user_id' => auth()->user()->id, // Assuming the product is created by the authenticated merchant
        ]);

        return ResponseHelpers::sendSuccess('Product created successfully', $product, 201);
    }

    // List all products for the authenticated merchant
    public function byMerchant()
    {
        $products = Product::where('user_id', auth()->user()->id)->get(); // Fetch products created by the authenticated merchant

        if ($products->isEmpty()) {
            return ResponseHelpers::sendError('No products found', [], 404);
        }

        return ResponseHelpers::sendSuccess('Products retrieved successfully', $products);
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ResponseHelpers::sendError('Product not found', [], 404);
        }

        // Make sure the product belongs to the authenticated merchant
        if ($product->user_id !== auth()->user()->id) {
            return ResponseHelpers::sendError('Unauthorized', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:1',
            'stock' => 'integer|min:1',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return ResponseHelpers::sendError('Validation Error', $validator->errors(), 422);
        }

        $product->update($request->only(['name', 'description', 'price', 'stock', 'image_url']));

        return ResponseHelpers::sendSuccess('Product updated successfully', $product);
    }

    // Delete a product
    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ResponseHelpers::sendError('Product not found', [], 404);
        }

        // Ensure that the product belongs to the authenticated merchant
        if ($product->user_id !== auth()->user()->id) {
            return ResponseHelpers::sendError('Unauthorized', [], 403);
        }

        $product->delete();

        return ResponseHelpers::sendSuccess('Product deleted successfully', []);
    }

    public function index()
    {
        $products = Product::get(); // Fetch products created by the authenticated merchant

        if ($products->isEmpty()) {
            return ResponseHelpers::sendError('No products found', [], 404);
        }

        return ResponseHelpers::sendSuccess('Products retrieved successfully', $products);
    }
}
