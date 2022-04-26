<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorage;

interface PriceProductOfferStorageWriterInterface
{
    /**
     * @param array<int> $priceProductOfferIds
     *
     * @return void
     */
    public function publish(array $priceProductOfferIds): void;

    /**
     * @param array<int> $priceProductOfferIdsWithOfferIds
     *
     * @return void
     */
    public function unpublish(array $priceProductOfferIdsWithOfferIds): void;

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishByProductIds(array $productIds): void;

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function unpublishByProductIds(array $productIds): void;

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByPriceProductStoreEvents(array $eventEntityTransfers): void;
}
