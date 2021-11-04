<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryGui\PageObject;

class CategoryEditPage extends Category
{
    /**
     * @var string
     */
    public const URL = '/category-gui/edit?id-category=';

    /**
     * @var string
     */
    public const TITLE = 'Edit category';

    /**
     * @var string
     */
    public const SUCCESS_MESSAGE = 'The category was updated successfully.';

    /**
     * @var string
     */
    public const SUBMIT_BUTTON = 'Save';

    /**
     * @param int $idCategory
     *
     * @return string
     */
    public static function getUrl(int $idCategory): string
    {
        return self::URL . $idCategory;
    }
}
