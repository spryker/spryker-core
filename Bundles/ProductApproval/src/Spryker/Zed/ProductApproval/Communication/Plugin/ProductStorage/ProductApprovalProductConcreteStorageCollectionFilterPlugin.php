<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Communication\Plugin\ProductStorage;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionFilterPluginInterface;

/**
 * @method \Spryker\Zed\ProductApproval\ProductApprovalConfig getConfig()
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface getFacade()
 */
class ProductApprovalProductConcreteStorageCollectionFilterPlugin extends AbstractPlugin implements ProductConcreteStorageCollectionFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters product concrete storage transfers by product approval status.
     * - Filters out products which have not `approved` status.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function filter(array $productConcreteStorageTransfers): array
    {
        return $this->getFacade()->filterProductConcreteStorageCollection($productConcreteStorageTransfers);
    }
}
