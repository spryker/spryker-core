<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPreCreatePluginInterface;

/**
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductApproval\ProductApprovalConfig getConfig()
 */
class ProductApprovalProductAbstractPreCreatePlugin extends AbstractPlugin implements ProductAbstractPreCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `ProductAbstract` transfer with default approval status if `ProductAbstract.approvalStatus` property is not set.
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
