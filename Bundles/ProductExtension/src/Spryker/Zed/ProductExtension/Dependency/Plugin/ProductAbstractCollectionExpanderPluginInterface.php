<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;

/**
 * Interface is used to expand the ProductAbstract collection with additional data.
 */
interface ProductAbstractCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands product abstract data in bulk.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function expand(
        ProductAbstractCollectionTransfer $productAbstractCollectionTransfer,
        ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
    ): ProductAbstractCollectionTransfer;
}
