<?php

namespace App\Http\Controllers\Api;

use App\DTO\Validations\Product\SaveProductData;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Product",
 *     description="Operations related with products"
 * )
 */
class ProductController extends Controller
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="Get product list",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function list(): AnonymousResourceCollection
    {
        return ProductResource::collection($this->repository->getAllProducts());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/products",
     *     summary="Create or update product",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function save(SaveProductData $data): ProductResource
    {
        return ProductResource::make($this->repository->saveProduct($data->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{product}",
     *     summary="Get product item",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function item(Product $product): ProductResource
    {

        return ProductResource::make($this->repository->loadRelations($product));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/products/{product}",
     *     summary="Delete product item",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function destroy(Product $product): Response
    {
        $product->delete();
        return response()->noContent();
    }
}
