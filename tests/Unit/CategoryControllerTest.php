<?php

namespace Tests\Unit;

use App\DTO\Validations\Category\SaveCategoryData;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws Exception
     */
    public function test_it_can_list_categories()
    {
        $categories = Category::factory(3)->create();

        $repository = $this->createMock(CategoryRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('getAllCategories')
            ->willReturn($categories);

        $controller = new CategoryController($repository);

        $response = $controller->list();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
    }

    /**
     * @throws Exception
     */
    public function test_it_can_get_category_with_relations()
    {
        $category = Category::factory()->create();

        $repository = $this->createMock(CategoryRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('loadRelations')
            ->willReturn($category);

        $controller = new CategoryController($repository);

        $response = $controller->item($category);

        $this->assertInstanceOf(CategoryResource::class, $response);
    }

    /**
     * @throws Exception
     */
    public function test_it_can_delete_category()
    {
        $category = Category::factory()->create();

        $repository = $this->createMock(CategoryRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('deleteCategory')
            ->willReturn(true);

        $controller = new CategoryController($repository);

        $response = $controller->destroy($category);

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @throws Exception
     */
    public function test_it_can_store_general_category()
    {

        $request = new SaveCategoryData('some general category');

        $repository = $this->createMock(CategoryRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('saveCategory')
            ->willReturn(new Category());

        $controller = new CategoryController($repository);

        $response = $controller->save($request);

        $this->assertInstanceOf(CategoryResource::class, $response);
    }

    /**
     * @throws Exception
     */
    public function test_it_can_store_subcategories_category()
    {
        $parent = Category::factory()->create();

        $request = new SaveCategoryData('Some child category', $parent->getKey());

        $repository = $this->createMock(CategoryRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('saveCategory')
            ->with($this->callback(function ($data) use ($parent) {
                return $data['parent_id'] === $parent->getKey();
            }))
            ->willReturn(new Category());

        $controller = new CategoryController($repository);
        $response = $controller->save($request);

        $this->assertInstanceOf(CategoryResource::class, $response);
    }
}

