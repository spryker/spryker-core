<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Publisher\ProductConcrete;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Communication\MerchantProductOfferStorageCommunicationFactory getFactory()
 */
class ProductConcreteDeletePublisherPlugin extends AbstractPlugin
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, string $eventName): void
    {
        $this->getFacade()->deleteProductConcreteProductOffersStorageCollectionByProductSkuEvents($transfers);
    }
}
