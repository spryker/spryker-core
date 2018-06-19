<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\Tree\Fixtures\Input;

use Spryker\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter;

class CategoryStructureInput
{
    /**
     * @return array
     */
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

    /**
     * @return array
     */
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

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function getCategoryStructureWithNonexistentParent()
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
