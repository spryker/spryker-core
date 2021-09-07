<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryGui\PageObject;

class CategoryListPage
{
    /**
     * @var string
     */
    public const URL = '/category-gui/root';

    /**
     * @var string
     */
    public const SELECTOR_TABLE = 'dataTables_wrapper';
    /**
     * @var string
     */
    public const SELECTOR_CATEGORIES_LIST = 'categories-list';

    /**
     * @var string
     */
    public const BUTTON_CREATE_CATEGORY = '//div[@class="title-action"]/a';

    /**
     * @var string
     */
    public const SELECTOR_TREE_LIST = '#category-tree > div.dd > ol.dd-list';

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getAssignProductsButtonSelector(int $position = 1): string
    {
        return sprintf('//a[@title="Assign Products to this Category"][%s]', $position);
    }

    /**
     * @param int $position
     *
     * @return string
     */
    public static function getDeleteButtonSelector(int $position = 1): string
    {
        return sprintf('//a[@title="Delete Category"][%s]', $position);
    }
}
