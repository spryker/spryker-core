<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer;

interface ProductSearchConfigStorageToProductSearchFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer
     */
    public function getProductSearchAttributeCollection(
        ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
    ): ProductSearchAttributeCollectionTransfer;
}
