<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\PageObject;

class CategoryEditPage extends Category
{
    public const URL = '/category/edit?id-category=';
    public const TITLE = 'Edit category';
    public const SUCCESS_MESSAGE = 'The category was updated successfully.';
    public const SUBMIT_BUTTON = 'Save';

    /**
     * @param int $idCategory
     *
     * @return string
     */
    public static function getUrl($idCategory)
    {
        return self::URL . $idCategory;
    }
}
