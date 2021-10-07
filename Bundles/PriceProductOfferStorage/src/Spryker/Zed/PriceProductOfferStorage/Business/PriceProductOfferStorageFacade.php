<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorageBusinessFactory getFactory()
 */
class PriceProductOfferStorageFacade extends AbstractFacade implements PriceProductOfferStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $priceProductOfferIds
     *
     * @return void
     */
    public function publish(array $priceProductOfferIds): void
    {
        $this->getFactory()->createPriceProductOfferStorageWriter()->publish($priceProductOfferIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $priceProductOfferIdsWithOfferIds
     *
     * @return void
     */
    public function unpublish(array $priceProductOfferIdsWithOfferIds): void
    {
        $this->getFactory()->createPriceProductOfferStorageWriter()->unpublish($priceProductOfferIdsWithOfferIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishByProductIds(array $productIds): void
    {
        $this->getFactory()->createPriceProductOfferStorageWriter()->publishByProductIds($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function unpublishByProductIds(array $productIds): void
    {
        $this->getFactory()->createPriceProductOfferStorageWriter()->unpublishByProductIds($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByPriceProductStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createPriceProductOfferStorageWriter()
            ->writeCollectionByPriceProductStoreEvents($eventEntityTransfers);
    }
}
