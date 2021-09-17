<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business;

interface PriceProductOfferStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all price product offers with the given priceProductOfferIds.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<int> $priceProductOfferIds
     *
     * @return void
     */
    public function publish(array $priceProductOfferIds): void;

    /**
     * Specification:
     * - Finds and deletes price product offer storage entities with the given priceProductOfferIds.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param array<int> $priceProductOfferIdsWithOfferIds
     *
     * @return void
     */
    public function unpublish(array $priceProductOfferIdsWithOfferIds): void;

    /**
     * Specification:
     * - Queries all price product offers with the given productIds.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishByProductIds(array $productIds): void;

    /**
     * Specification:
     * - Finds and deletes price product offer storage entities with the given concreteProductIds.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function unpublishByProductIds(array $productIds): void;

    /**
     * Specification:
     * - Extracts price product store IDs from the $eventTransfers created by price product store entity events.
     * - Queries all price product offers with the extracted price product store IDs.
     * - Stores data as JSON encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByPriceProductStoreEvents(array $eventEntityTransfers): void;
}
