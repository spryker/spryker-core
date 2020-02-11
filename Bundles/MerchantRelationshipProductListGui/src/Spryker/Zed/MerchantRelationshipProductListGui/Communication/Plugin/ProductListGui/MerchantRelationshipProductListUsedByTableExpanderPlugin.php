<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin\ProductListGui;

use Generated\Shared\Transfer\ProductListUsedByTableTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListUsedByTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Communication\MerchantRelationshipProductListGuiCommunicationFactory getFactory()
 */
class MerchantRelationshipProductListUsedByTableExpanderPlugin extends AbstractPlugin implements ProductListUsedByTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands table data with Merchant Relationships which use Product List.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListUsedByTableTransfer $productListUsedByTableTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableTransfer
     */
    public function expand(ProductListUsedByTableTransfer $productListUsedByTableTransfer): ProductListUsedByTableTransfer
    {
        return $this->getFactory()
            ->createProductListUsedByTableExpander()
            ->expandTableData($productListUsedByTableTransfer);
    }
}
