<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

class ProductCategoryFilterDeleter implements ProductCategoryFilterDeleterInterface
{
    use RetrievesProductCategoryFilterEntity;

    /**
     * @param int $categoryId
     *
     * @return void
     */
    public function deleteProductCategoryFilterByCategoryId($categoryId)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($categoryId);
        $productCategoryFilterEntity->delete();
    }
}
