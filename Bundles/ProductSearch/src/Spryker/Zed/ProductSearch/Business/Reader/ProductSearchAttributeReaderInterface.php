<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Reader;

use Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer;

interface ProductSearchAttributeReaderInterface
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
