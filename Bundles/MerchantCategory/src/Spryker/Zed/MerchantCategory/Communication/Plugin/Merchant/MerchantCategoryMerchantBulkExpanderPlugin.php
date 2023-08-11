<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantCategory\Business\MerchantCategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantCategory\MerchantCategoryConfig getConfig()
 */
class MerchantCategoryMerchantBulkExpanderPlugin extends AbstractPlugin implements MerchantBulkExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantCollectionTransfer.merchants.idMerchant` to be set.
     * - Retrieves merchant category data from Persistence by provided `Merchant.idMerchant` from collection.
     * - Expands each `MerchantTransfer` from `MerchantCollectionTransfer` with related list of `CategoryTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expand(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        return $this->getFacade()->expandMerchantCollectionWithCategories($merchantCollectionTransfer);
    }
}
