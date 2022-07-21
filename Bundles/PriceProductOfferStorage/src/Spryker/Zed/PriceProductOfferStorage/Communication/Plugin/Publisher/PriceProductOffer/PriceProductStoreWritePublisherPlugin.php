<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Publisher\PriceProductOffer;

use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOfferStorage\Communication\PriceProductOfferStorageCommunicationFactory getFactory()
 */
class PriceProductStoreWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes product offer prices data by update events from spy_price_product_store table.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByPriceProductStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            PriceProductOfferStorageConfig::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE,
        ];
    }
}
