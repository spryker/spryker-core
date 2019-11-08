<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;

interface PriceProductOfferStorageFacadeInterface
{
    /**
     * Specification:
     * - Gets prices by idProductOffer.
     * - Expands ProductOfferStorageTransfer with product offer prices.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expandWithProductOfferPrices(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer;

    /**
     * Specification:
     * - Queries all price product offers with the given priceProductOfferIds.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param int[] $priceProductOfferIds
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
     * @param int[] $priceProductOfferIdsWithOfferIds
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
     * @param int[] $productIds
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
     * @param int[] $productIds
     *
     * @return void
     */
    public function unpublishByProductIds(array $productIds): void;
}
