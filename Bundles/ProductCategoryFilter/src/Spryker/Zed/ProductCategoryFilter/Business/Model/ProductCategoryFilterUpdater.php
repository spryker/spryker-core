<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

class ProductCategoryFilterUpdater implements ProductCategoryFilterUpdaterInterface
{
    use RetrievesProductCategoryFilterEntity;

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function updateProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($productCategoryFilterTransfer->getFkCategory());

        $productCategoryFilterEntity->fromArray($productCategoryFilterTransfer->modifiedToArray());
        $productCategoryFilterEntity->save();

        return $productCategoryFilterTransfer->fromArray($productCategoryFilterEntity->toArray(), true);
    }
}
