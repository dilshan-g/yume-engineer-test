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
     * @param integer $id
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                return response(['message' => 'Product ID must be an Integer!.'], 422);
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
}
