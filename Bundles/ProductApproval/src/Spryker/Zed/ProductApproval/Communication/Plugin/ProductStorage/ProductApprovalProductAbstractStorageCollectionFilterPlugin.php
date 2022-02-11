<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Communication\Plugin\ProductStorage;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageCollectionFilterPluginInterface;

/**
 * @method \Spryker\Zed\ProductApproval\ProductApprovalConfig getConfig()
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface getFacade()
 */
class ProductApprovalProductAbstractStorageCollectionFilterPlugin extends AbstractPlugin implements ProductAbstractStorageCollectionFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters product abstract storage transfers by product approval status.
     * - Filters out abstract products which have not `approved` status.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer> $productAbstractStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer>
     */
    public function filter(array $productAbstractStorageTransfers): array
    {
        return $this->getFacade()->filterProductAbstractStorageCollection($productAbstractStorageTransfers);
    }
}
