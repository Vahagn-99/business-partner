<?php

namespace App\Repositories\Category;

use App\Exceptions\CategoryLevelLimitException;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories(): Collection
    {
        return Category::query()->get();
    }

    /**
     * @throws CategoryLevelLimitException
     */
    public function saveCategory(array $data): Category|Model
    {
        if ($data['parent_id']) {
            /** @var Category $parent */
            $parent = Category::query()->find($data['parent_id']);
            if ($parent->level >= CategoryRepositoryInterface::CATEGORY_LIMIT) {
                throw  new CategoryLevelLimitException('The category level limit was exceeded.', 419);
            } else {
                $data['level'] = $parent->level + 1;
            }
        }
        /** @var Category $category */
        $category = Category::query()->updateOrCreate(['id' => $data['id']], $data);
        return $category;
    }

    public function deleteCategory(Category $category): bool
    {
        return $category->delete();
    }

    public function loadRelations(Category $category): Category|Model
    {
        return $category->load(['parent', 'subcategories']);
    }
}
