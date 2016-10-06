<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed\PageObject;

class CategoryEditPage extends Category
{

    const URL = '/category/edit?id-category=';

    /**
     * @param int $idCategory
     *
     * @return string
     */
    public function getUrl($idCategory)
    {
        return self::URL . $idCategory;
    }

}
