<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Communication\Plugin\ProductPageSearch;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcreteCollectionFilterPluginInterface;

/**
 * @method \Spryker\Zed\ProductApproval\ProductApprovalConfig getConfig()
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface getFacade()
 */
class ProductApprovalProductConcreteCollectionFilterPlugin extends AbstractPlugin implements ProductConcreteCollectionFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters product concrete transfers by product approval status.
     * - Filters out products which have not `approved` status.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function filter(array $productConcreteTransfers): array
    {
        return $this->getFacade()->filterProductConcreteCollection($productConcreteTransfers);
    }
}
