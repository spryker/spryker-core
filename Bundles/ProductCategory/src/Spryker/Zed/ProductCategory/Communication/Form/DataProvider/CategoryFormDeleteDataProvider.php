<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Form\DataProvider;

use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormDelete;

/**
 * @deprecated Will be removed with the next major release
 */
class CategoryFormDeleteDataProvider extends CategoryFormEditDataProvider
{

    /**
     * @param int|null $idCategory
     *
     * @return array
     */
    public function getData($idCategory)
    {
        $data = parent::getData($idCategory);
        $data[CategoryFormDelete::FIELD_FK_PARENT_CATEGORY_NODE] = null;

        return $data;
    }

}
