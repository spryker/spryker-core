<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductApproval\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPreCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductApproval\Business\MerchantProductApprovalFacadeInterface getFacade()()
 */
class MerchantProductApprovalProductAbstractPreCreatePlugin extends AbstractPlugin implements ProductAbstractPreCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands product abstract transfer with default merchant product approval status when `ProductAbstractTransfer::approvalStatus` is null.
     * - Does not expand product abstract transfer when `ProductAbstractTransfer::idMerchant` is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function preCreate(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        return $this->getFacade()->expandProductAbstract($productAbstractTransfer);
    }
}
