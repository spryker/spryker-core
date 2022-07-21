<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Communication\Plugin\Publisher\ProductConcreteOffers;

use Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferStorage\ProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferStorage\Communication\ProductOfferStorageCommunicationFactory getFactory()
 */
class ProductConcreteProductOffersStoreDeletePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Unpublishes product offer reference from `spy_product_concrete_product_offers_storage` by provided `EventEntity.foreign_keys.fk_product_offer` and `EventEntity.foreign_keys.fk_store` transfer properties.
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
        $this->getFacade()->writeProductConcreteProductOffersStorageCollectionByProductOfferStoreEvents($eventEntityTransfers);
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
            ProductOfferStorageConfig::PRODUCT_OFFER_STORE_UNPUBLISH,
            ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE,
        ];
    }
}
