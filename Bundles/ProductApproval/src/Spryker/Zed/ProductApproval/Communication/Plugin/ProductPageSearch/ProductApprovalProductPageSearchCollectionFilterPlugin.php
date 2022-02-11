<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Communication\Plugin\ProductPageSearch;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageSearchCollectionFilterPluginInterface;

/**
 * @method \Spryker\Zed\ProductApproval\ProductApprovalConfig getConfig()
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface getFacade()
 */
class ProductApprovalProductPageSearchCollectionFilterPlugin extends AbstractPlugin implements ProductPageSearchCollectionFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters product page search transfers by product approval status.
     * - Filters out products which have not `approved` status.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductPageSearchTransfer> $productPageSearchTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductPageSearchTransfer>
     */
    public function filter(array $productPageSearchTransfers): array
    {
        return $this->getFacade()->filterProductPageSearchCollection($productPageSearchTransfers);
    }
}
