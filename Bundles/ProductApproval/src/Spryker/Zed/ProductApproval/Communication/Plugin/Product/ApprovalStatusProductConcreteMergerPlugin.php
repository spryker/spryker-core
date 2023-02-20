<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteMergerPluginInterface;

/**
 * @method \Spryker\Zed\ProductApproval\ProductApprovalConfig getConfig()
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface getFacade()
 */
class ApprovalStatusProductConcreteMergerPlugin extends AbstractPlugin implements ProductConcreteMergerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Merges approvalStatus from ProductAbstractTransfer into ProductConcreteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function merge(ProductConcreteTransfer $productConcreteTransfer, ProductAbstractTransfer $productAbstractTransfer): ProductConcreteTransfer
    {
        return $productConcreteTransfer->setApprovalStatus($productAbstractTransfer->getApprovalStatus());
    }
}
