<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Communication\Plugin\MerchantRelationship;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListConfig getConfig()
 */
class ProductListMerchantRelationshipExpanderPlugin extends AbstractPlugin implements MerchantRelationshipExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantRelationshipTransfer.idMerchantRelationship` transfer property to be set.
     * - Expands `MerchantRelationshipTransfer` with product lists available for given merchant relationship.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function expand(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return $this->getFacade()->expandMerchantRelationship($merchantRelationshipTransfer);
    }
}
