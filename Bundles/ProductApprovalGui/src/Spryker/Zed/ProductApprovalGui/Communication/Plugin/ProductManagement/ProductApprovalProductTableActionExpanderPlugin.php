<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableActionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductApprovalGui\Communication\ProductApprovalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductApprovalGui\ProductApprovalGuiConfig getConfig()
 */
class ProductApprovalProductTableActionExpanderPlugin extends AbstractPlugin implements ProductTableActionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands product table with abstract product approval status actions.
     * - Renders action buttons taken from `ProductApprovalGuiConfig::getProductApprovalTableActionStatusTree()`, if `ProductApprovalGuiConfig::isApprovalStatusTreeCustomizationEnabled()` is true.
     * - Renders action buttons taken from `ProductApprovalFacade::getApplicableApprovalStatuses()` otherwise.
     *
     * @api
     *
     * @param array<mixed> $productData
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    public function execute(array $productData, ButtonCollectionTransfer $buttonCollectionTransfer): ButtonCollectionTransfer
    {
        return $this->getFactory()
            ->createProductApprovalProductTableActionExpander()
            ->expandWithProductApprovalStatusActions($productData, $buttonCollectionTransfer);
    }
}
