<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage\Plugin\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface;

/**
 * @method \Spryker\Client\MerchantStorage\MerchantStorageFactory getFactory()
 * @method \Spryker\Client\MerchantStorage\MerchantStorageClientInterface getClient()
 */
class MerchantProductOfferStorageExpanderPlugin extends AbstractPlugin implements ProductOfferStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns `ProductOfferStorage` transfer object expanded with `Merchant`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expand(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer
    {
        return $this->getClient()->expandProductOfferStorage($productOfferStorageTransfer);
    }
}
