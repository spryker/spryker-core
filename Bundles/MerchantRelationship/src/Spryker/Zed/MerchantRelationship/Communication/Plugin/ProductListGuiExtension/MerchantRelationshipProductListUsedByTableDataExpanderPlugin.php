<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Communication\Plugin\ProductListGuiExtension;

use Generated\Shared\Transfer\ProductListUsedByTableDataTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListUsedByTableDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationship\Communication\MerchantRelationshipCommunicationFactory getFactory()
 */
class MerchantRelationshipProductListUsedByTableDataExpanderPlugin extends AbstractPlugin implements ProductListUsedByTableDataExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands table data with Merchant Relationships which use Product List.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    public function expand(ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer): ProductListUsedByTableDataTransfer
    {
        return $this->getFacade()->expandProductListUsedByTableData($productListUsedByTableDataTransfer);
    }
}
