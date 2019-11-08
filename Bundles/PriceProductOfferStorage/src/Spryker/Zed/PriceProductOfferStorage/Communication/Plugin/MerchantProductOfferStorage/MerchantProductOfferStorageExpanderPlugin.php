<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProductOfferStorageExtension\Dependency\Plugin\MerchantProductOfferStorageExpanderPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOfferStorage\Communication\PriceProductOfferStorageCommunicationFactory getFactory()
 */
class MerchantProductOfferStorageExpanderPlugin extends AbstractPlugin implements MerchantProductOfferStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductOfferStorageTransfer with product offer prices.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expand(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer
    {
        return $this->getFacade()->expandWithProductOfferPrices($productOfferStorageTransfer);
    }
}
