<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

class ProductCategoryFilterReader implements ProductCategoryFilterReaderInterface
{
    use RetrievesProductCategoryFilterEntity;

    /**
     * @param int $categoryId
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function findProductCategoryFilterByCategoryId($categoryId)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($categoryId);

        if (!$productCategoryFilterEntity) {
            return null;
        }

        $productCategoryFilterTransfer = new ProductCategoryFilterTransfer();

        return $productCategoryFilterTransfer->fromArray($productCategoryFilterEntity->toArray(), true);
    }
}
