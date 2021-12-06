<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;

interface CategoryDiscountConnectorToProductCategoryFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function getProductCategoryCollection(ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer): ProductCategoryCollectionTransfer;
}
