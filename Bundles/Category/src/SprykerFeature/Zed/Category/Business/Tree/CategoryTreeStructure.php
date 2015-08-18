<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

class CategoryTreeStructure
{
    const ID = 'id';
    const PARENT = 'parent';
    const ROOT = 'root';
    const TEXT = 'text';
    const CHILDREN = 'children';

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
     *
     * @return $this
     */
    public function loadCategoriesArray(array $categories)
    {
        foreach ($categories as $category) {
            $this->setCategoryIndex($category);
            $this->setCategoryChildren($category);
        }

        $this->indexData();

        return $this;
    }

    /**
     * @param array $category
     */
    protected function setCategoryIndex(array $category)
    {
        $idCategory = $category[static::ID];
        $this->categoryIndex[$idCategory] = $this->fixValues($category);
    }

    /**
     * @param array $category
     *
     * @return array
     */
    protected function fixValues(array $category)
    {
        $category[static::TEXT] = stripslashes($category[static::TEXT]);

        return $category;
    }

    /**
     * @param array $category
     */
    protected function setCategoryChildren(array $category)
    {
        $parent = static::ROOT;
        if (!empty($category[static::PARENT])) {
            $parent = $category[static::PARENT];
        }

        $this->categoryChildren[$parent][] = $category[static::ID];
    }

    /**
     * @return CategoryTreeStructure
     */
    protected function indexData()
    {
        $this->categories = $this->processChildren(static::ROOT);

        return $this;
    }

    /**
     * @param string|id $parent
     *
     * @return array
     */
    protected function processChildren($parent)
    {
        if (!array_key_exists($parent, $this->categoryChildren)) {
            return [];
        }

        $children = [];

        foreach ($this->categoryChildren[$parent] as $category) {
            $children[$category] = array_merge($this->categoryIndex[$category], [static::CHILDREN => []]);
            $children[$category][static::CHILDREN] = $this->processChildren($category);
        }

        return $children;
    }

    /**
     * @return array
     */
    public function getCategoriesTree()
    {
        return $this->categories;
    }
}
