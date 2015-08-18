<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Category\Business\Tree\Fixtures\Input;

use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeStructure;

class CategoryStructureInput
{
    public function getOrderedCategoriesArray()
    {
        $categories = [
            [
                CategoryTreeStructure::ID => 1,
                CategoryTreeStructure::PARENT => 0,
                CategoryTreeStructure::TEXT => 'Category 1',
            ],
            [
                CategoryTreeStructure::ID => 2,
                CategoryTreeStructure::PARENT => 1,
                CategoryTreeStructure::TEXT => 'Category 2',
            ],
            [
                CategoryTreeStructure::ID => 3,
                CategoryTreeStructure::PARENT => 2,
                CategoryTreeStructure::TEXT => 'Category 3',
            ],
            [
                CategoryTreeStructure::ID => 4,
                CategoryTreeStructure::PARENT => 1,
                CategoryTreeStructure::TEXT => 'Category 4',
            ],
            [
                CategoryTreeStructure::ID => 5,
                CategoryTreeStructure::PARENT => 3,
                CategoryTreeStructure::TEXT => 'Category 5',
            ],
            [
                CategoryTreeStructure::ID => 6,
                CategoryTreeStructure::PARENT => 5,
                CategoryTreeStructure::TEXT => 'Category 6',
            ],
        ];

        return $categories;
    }

    public function getSecondOrderedCategoriesArray()
    {
        $categories = [
            [
                CategoryTreeStructure::ID => 1,
                CategoryTreeStructure::PARENT => 0,
                CategoryTreeStructure::TEXT => 'Category 1',
            ],
            [
                CategoryTreeStructure::ID => 2,
                CategoryTreeStructure::PARENT => 1,
                CategoryTreeStructure::TEXT => 'Category 2',
            ],
            [
                CategoryTreeStructure::ID => 3,
                CategoryTreeStructure::PARENT => 2,
                CategoryTreeStructure::TEXT => 'Category 3',
            ],
            [
                CategoryTreeStructure::ID => 4,
                CategoryTreeStructure::PARENT => 1,
                CategoryTreeStructure::TEXT => 'Category 4',
            ],
            [
                CategoryTreeStructure::ID => 5,
                CategoryTreeStructure::PARENT => 2,
                CategoryTreeStructure::TEXT => 'Category 5',
            ],
            [
                CategoryTreeStructure::ID => 6,
                CategoryTreeStructure::PARENT => 4,
                CategoryTreeStructure::TEXT => 'Category 6',
            ],
        ];

        return $categories;
    }

    public function getCategoryStructureWithChildrenBeforeParent()
    {
        $categories = [
            [
                CategoryTreeStructure::ID => 1,
                CategoryTreeStructure::PARENT => 0,
                CategoryTreeStructure::TEXT => 'Category 1',
            ],
            [
                CategoryTreeStructure::ID => 2,
                CategoryTreeStructure::PARENT => 1,
                CategoryTreeStructure::TEXT => 'Category 2',
            ],
            [
                CategoryTreeStructure::ID => 3,
                CategoryTreeStructure::PARENT => 6,
                CategoryTreeStructure::TEXT => 'Category 3',
            ],
            [
                CategoryTreeStructure::ID => 4,
                CategoryTreeStructure::PARENT => 1,
                CategoryTreeStructure::TEXT => 'Category 4',
            ],
            [
                CategoryTreeStructure::ID => 5,
                CategoryTreeStructure::PARENT => 2,
                CategoryTreeStructure::TEXT => 'Category 5',
            ],
            [
                CategoryTreeStructure::ID => 6,
                CategoryTreeStructure::PARENT => 1,
                CategoryTreeStructure::TEXT => 'Category 6',
            ],
        ];

        return $categories;
    }

    public function getCategoryStructureWithNonexistantParent()
    {
        $categories = [
            [
                CategoryTreeStructure::ID => 1,
                CategoryTreeStructure::PARENT => 0,
                CategoryTreeStructure::TEXT => 'Category 1',
            ],
            [
                CategoryTreeStructure::ID => 2,
                CategoryTreeStructure::PARENT => 1,
                CategoryTreeStructure::TEXT => 'Category 2',
            ],
            [
                CategoryTreeStructure::ID => 3,
                CategoryTreeStructure::PARENT => 7,
                CategoryTreeStructure::TEXT => 'Category 3',
            ],
            [
                CategoryTreeStructure::ID => 4,
                CategoryTreeStructure::PARENT => 1,
                CategoryTreeStructure::TEXT => 'Category 4',
            ],
            [
                CategoryTreeStructure::ID => 5,
                CategoryTreeStructure::PARENT => 2,
                CategoryTreeStructure::TEXT => 'Category 5',
            ],
            [
                CategoryTreeStructure::ID => 6,
                CategoryTreeStructure::PARENT => 2,
                CategoryTreeStructure::TEXT => 'Category 6',
            ],
        ];

        return $categories;
    }
}
