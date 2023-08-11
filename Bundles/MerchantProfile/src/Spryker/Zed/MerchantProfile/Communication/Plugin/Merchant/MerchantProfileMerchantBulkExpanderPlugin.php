<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfile\MerchantProfileConfig getConfig()
 */
class MerchantProfileMerchantBulkExpanderPlugin extends AbstractPlugin implements MerchantBulkExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantCollectionTransfer.merchants.idMerchant` to be set.
     * - Retrieves merchant profile data from Persistence by provided `Merchant.idMerchant` from collection.
     * - Expands each `MerchantTransfer` from `MerchantCollectionTransfer` with related `MerchantProfileTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expand(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        return $this->getFacade()->expandMerchantCollectionWithMerchantProfile($merchantCollectionTransfer);
    }
}
