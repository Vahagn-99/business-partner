<?php

namespace App\Http\Controllers\Api;

use App\DTO\Validations\Category\SaveCategoryData;
use App\Exceptions\CategoryLevelLimitException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Category",
 *     description="Operations related with categories"
 * )
 */
class CategoryController extends Controller
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="The categories list",
     *     tags={"Category"},
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
        return CategoryResource::collection($this->repository->getAllCategories());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     summary="Add category",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function save(SaveCategoryData $data): CategoryResource|JsonResponse
    {
        try {
            return CategoryResource::make($this->repository->saveCategory($data->all()));
        } catch (CategoryLevelLimitException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/categories/{category}",
     *     summary="Get category item",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function item(Category $category): CategoryResource
    {
        return CategoryResource::make($this->repository->loadRelations($category));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{category}",
     *     summary="Delete category item",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function destroy(Category $category): Response
    {
        $this->repository->deleteCategory($category);
        return response()->noContent();
    }
}
