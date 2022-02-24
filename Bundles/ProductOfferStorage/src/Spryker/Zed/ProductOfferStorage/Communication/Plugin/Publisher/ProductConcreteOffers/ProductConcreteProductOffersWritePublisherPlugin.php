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
class ProductConcreteProductOffersWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $this->getFacade()->writeProductConcreteProductOffersStorageCollectionByProductEvents($eventEntityTransfers);
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
            ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_CREATE,
            ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_UPDATE,
            ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_DELETE,
            ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE,
            ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_UPDATE,
            ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE,
            ProductOfferStorageConfig::PRODUCT_OFFER_PUBLISH,
            ProductOfferStorageConfig::PRODUCT_OFFER_UNPUBLISH,
            ProductOfferStorageConfig::PRODUCT_OFFER_STORE_PUBLISH,
            ProductOfferStorageConfig::PRODUCT_OFFER_STORE_UNPUBLISH,
        ];
    }
}
