<?php

namespace App\Http\Controllers\Api;

use App\DTO\Validations\Product\AddProductToCartData;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Product;
use App\Repositories\Cart\CartRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Cart",
 *     description="Operations related to adding product to cart"
 * )
 */
class CartController extends Controller
{
    private CartRepositoryInterface $repository;

    public function __construct(CartRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/cart",
     *     summary="The user cart products",
     *     tags={"Cart"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function list(): CartResource
    {
        return CartResource::make($this->repository->userCart(Auth::user()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/cart",
     *     summary="Add product to  cart",
     *     tags={"Cart"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function save(AddProductToCartData $data): CartResource
    {
        return CartResource::make($this->repository->addToCart(Auth::user(), $data->all()));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/cart/{product}",
     *     summary="delete product from cart",
     *     tags={"Cart"},
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
        $this->repository->removeFromCart(Auth::user(), $product);
        return response()->noContent();
    }
}
