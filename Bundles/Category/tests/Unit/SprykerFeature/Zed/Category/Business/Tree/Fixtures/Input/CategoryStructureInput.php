<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Category\Business\Tree\Fixtures\Input;

use SprykerFeature\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter;

class CategoryStructureInput
{

    public function getOrderedCategoriesArray()
    {
        $categories = [
            [
                CategoryTreeFormatter::ID => 1,
                CategoryTreeFormatter::ID_PARENT => 0,
                CategoryTreeFormatter::TEXT => 'Category 1',
            ],
            [
                CategoryTreeFormatter::ID => 2,
                CategoryTreeFormatter::ID_PARENT => 1,
                CategoryTreeFormatter::TEXT => 'Category 2',
            ],
            [
                CategoryTreeFormatter::ID => 3,
                CategoryTreeFormatter::ID_PARENT => 2,
                CategoryTreeFormatter::TEXT => 'Category 3',
            ],
            [
                CategoryTreeFormatter::ID => 4,
                CategoryTreeFormatter::ID_PARENT => 1,
                CategoryTreeFormatter::TEXT => 'Category 4',
            ],
            [
                CategoryTreeFormatter::ID => 5,
                CategoryTreeFormatter::ID_PARENT => 3,
                CategoryTreeFormatter::TEXT => 'Category 5',
            ],
            [
                CategoryTreeFormatter::ID => 6,
                CategoryTreeFormatter::ID_PARENT => 5,
                CategoryTreeFormatter::TEXT => 'Category 6',
            ],
        ];

        return $categories;
    }

    public function getSecondOrderedCategoriesArray()
    {
        $categories = [
            [
                CategoryTreeFormatter::ID => 1,
                CategoryTreeFormatter::ID_PARENT => 0,
                CategoryTreeFormatter::TEXT => 'Category 1',
            ],
            [
                CategoryTreeFormatter::ID => 2,
                CategoryTreeFormatter::ID_PARENT => 1,
                CategoryTreeFormatter::TEXT => 'Category 2',
            ],
            [
                CategoryTreeFormatter::ID => 3,
                CategoryTreeFormatter::ID_PARENT => 2,
                CategoryTreeFormatter::TEXT => 'Category 3',
            ],
            [
                CategoryTreeFormatter::ID => 4,
                CategoryTreeFormatter::ID_PARENT => 1,
                CategoryTreeFormatter::TEXT => 'Category 4',
            ],
            [
                CategoryTreeFormatter::ID => 5,
                CategoryTreeFormatter::ID_PARENT => 2,
                CategoryTreeFormatter::TEXT => 'Category 5',
            ],
            [
                CategoryTreeFormatter::ID => 6,
                CategoryTreeFormatter::ID_PARENT => 4,
                CategoryTreeFormatter::TEXT => 'Category 6',
            ],
        ];

        return $categories;
    }

    public function getCategoryStructureWithChildrenBeforeParent()
    {
        $categories = [
            [
                CategoryTreeFormatter::ID => 1,
                CategoryTreeFormatter::ID_PARENT => 0,
                CategoryTreeFormatter::TEXT => 'Category 1',
            ],
            [
                CategoryTreeFormatter::ID => 2,
                CategoryTreeFormatter::ID_PARENT => 1,
                CategoryTreeFormatter::TEXT => 'Category 2',
            ],
            [
                CategoryTreeFormatter::ID => 3,
                CategoryTreeFormatter::ID_PARENT => 6,
                CategoryTreeFormatter::TEXT => 'Category 3',
            ],
            [
                CategoryTreeFormatter::ID => 4,
                CategoryTreeFormatter::ID_PARENT => 1,
                CategoryTreeFormatter::TEXT => 'Category 4',
            ],
            [
                CategoryTreeFormatter::ID => 5,
                CategoryTreeFormatter::ID_PARENT => 2,
                CategoryTreeFormatter::TEXT => 'Category 5',
            ],
            [
                CategoryTreeFormatter::ID => 6,
                CategoryTreeFormatter::ID_PARENT => 1,
                CategoryTreeFormatter::TEXT => 'Category 6',
            ],
        ];

        return $categories;
    }

    public function getCategoryStructureWithNonexistantParent()
    {
        $categories = [
            [
                CategoryTreeFormatter::ID => 1,
                CategoryTreeFormatter::ID_PARENT => 0,
                CategoryTreeFormatter::TEXT => 'Category 1',
            ],
            [
                CategoryTreeFormatter::ID => 2,
                CategoryTreeFormatter::ID_PARENT => 1,
                CategoryTreeFormatter::TEXT => 'Category 2',
            ],
            [
                CategoryTreeFormatter::ID => 3,
                CategoryTreeFormatter::ID_PARENT => 7,
                CategoryTreeFormatter::TEXT => 'Category 3',
            ],
            [
                CategoryTreeFormatter::ID => 4,
                CategoryTreeFormatter::ID_PARENT => 1,
                CategoryTreeFormatter::TEXT => 'Category 4',
            ],
            [
                CategoryTreeFormatter::ID => 5,
                CategoryTreeFormatter::ID_PARENT => 2,
                CategoryTreeFormatter::TEXT => 'Category 5',
            ],
            [
                CategoryTreeFormatter::ID => 6,
                CategoryTreeFormatter::ID_PARENT => 2,
                CategoryTreeFormatter::TEXT => 'Category 6',
            ],
        ];

        return $categories;
    }

}
