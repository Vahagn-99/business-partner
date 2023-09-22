<?php

namespace App\Repositories\Category;

use App\Exceptions\CategoryLevelLimitException;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CategoryRepositoryInterface
{
    public const CATEGORY_LIMIT = 10;

    public function getAllCategories(): Collection;

    /**
     * @throws CategoryLevelLimitException
     */
    public function saveCategory(array $data): Category|Model;

    public function loadRelations(Category $category): Category|Model;

    public function deleteCategory(Category $category): bool;
}
