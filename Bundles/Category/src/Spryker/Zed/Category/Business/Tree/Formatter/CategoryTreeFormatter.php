<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree\Formatter;

class CategoryTreeFormatter
{
    public const ID = 'id';
    public const ID_PARENT = 'parent';
    public const ROOT = 0;
    public const TEXT = 'text';
    public const CHILDREN = 'children';

    /**
     * @var array
     */
    protected $categoryIndex = [];

    /**
     * @var array
     */
    protected $categoryChildren = [];

    /**
     * @var array
     */
    protected $categories = [];

    /**
     * @param array $categories
     */
    public function __construct(array $categories = [])
    {
        $this->setupCategories($categories);
    }

    /**
     * @param array $categories
     *
     * @return void
     */
    public function setupCategories(array $categories)
    {
        foreach ($categories as $category) {
            $this->findCategoryIndex($category);
            $this->findCategoryChildren($category);
        }

        $this->structureArray();
    }

    /**
     * @param array $category
     *
     * @return void
     */
    protected function findCategoryIndex(array $category)
    {
        $idCategory = $category[static::ID];
        $this->categoryIndex[$idCategory] = $this->filterValues($category);
    }

    /**
     * @param array $category
     *
     * @return array
     */
    protected function filterValues(array $category)
    {
        $category[static::TEXT] = stripslashes($category[static::TEXT]);

        return $category;
    }

    /**
     * @param array $category
     *
     * @return void
     */
    protected function findCategoryChildren(array $category)
    {
        $parent = static::ROOT;
        if (!empty($category[static::ID_PARENT])) {
            $parent = $category[static::ID_PARENT];
        }

        $this->categoryChildren[$parent][] = $category[static::ID];
    }

    /**
     * @return $this
     */
    protected function structureArray()
    {
        $this->categories = $this->addChildrenToParents(static::ROOT);

        return $this;
    }

    /**
     * @param int $parent
     *
     * @return array
     */
    protected function addChildrenToParents($parent)
    {
        if (!array_key_exists($parent, $this->categoryChildren)) {
            return [];
        }

        $children = [];

        foreach ($this->categoryChildren[$parent] as $category) {
            $children[$category] = array_merge($this->categoryIndex[$category], [static::CHILDREN => []]);
            $children[$category][static::CHILDREN] = $this->addChildrenToParents($category);
        }

        return $children;
    }

    /**
     * @return array
     */
    public function getCategoryTree()
    {
        return $this->categories;
    }
}
