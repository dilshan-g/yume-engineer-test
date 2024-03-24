<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Returns all the products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Product::all());
    }

    /**
     * Selects a product by ID.
     *
     * @param $id
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                return response(['message' => 'Product ID must be an Integer.'], 422);
            }
            $product = Product::find($id);
            if (is_null($product)) {
                return response(['message' => 'The Product not found.'], 422);
            }
            return response($product, 200);

        } catch (QueryException $e) {
            return response([$e->getMessage()], 200);
        }

    }

    /**
     * Creates a product.
     *
     * @param Request $request
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function store(Request $request)
    {
        try {
            // Validations for the `name` and `price` fields.
            $request->validate([
                'name' => 'required|min:3',
                'price' => 'decimal:2'
            ]);

            $product = Product::create($request->all());

            if (is_null($product)) {
                return response(['message' => 'Unable to create the Product.'], 400);
            }
            return response(['message' => 'Product created successfully.', 'payload' => $product], 200);

        } catch (QueryException $e) {
            return response([$e->getMessage()], 200);
        }

    }

    /**
     * Updates product by ID.
     *
     * @param Request $request
     * @param $id
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function update(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                return response(['message' => 'Product ID must be an Integer.'], 422);
            }

            $request->validate([
                'name' => 'required|min:3',
                'price' => 'decimal:2'
            ]);

            $product = Product::find($id);

            if (!empty($product)) {
                $product->update($request->all());
                return response(['message' => 'The product updated successfully.', 'payload' => $product], 200);
            } else {
                return response(['message' => 'The Product not found.'], 422);
            }

        } catch (QueryException $e) {
            return response([$e->getMessage()], 200);
        }

    }

    /**
     * Deletes product by ID
     *
     * @param $id
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function delete($id)
    {
        try {
            if (!is_numeric($id)) {
                return response(['message' => 'Product ID must be an Integer.'], 422);
            }

            $product = Product::find($id);

            if (!empty($product)) {
                $product->delete();
                return response('', 204);
            } else {
                return response(['message' => 'The Product not found.'], 422);
            }

        } catch (QueryException $e) {
            return response([$e->getMessage()], 200);
        }

    }
}
