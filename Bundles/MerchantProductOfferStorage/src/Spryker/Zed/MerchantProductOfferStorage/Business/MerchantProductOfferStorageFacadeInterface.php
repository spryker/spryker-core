<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business;

interface MerchantProductOfferStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all product offer with the given concreteSkus.
     * - Lists of product references for concrete sku.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param string[] $productSkus
     *
     * @return void
     */
    public function publishProductConcreteProductOffersStorage(array $productSkus): void;

    /**
     * Specification:
     * - Finds and deletes product concrete offer storage entities with the given concreteSkus.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param string[] $productSkus
     *
     * @return void
     */
    public function unpublishProductConcreteProductOffersStorage(array $productSkus): void;

    /**
     * Specification:
     * - Queries all product offer with the given productOfferReferences.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function publishProductOfferStorage(array $productOfferReferences): void;

    /**
     * Specification:
     * - Finds and deletes product offer storage entities with the given productOfferReferences.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function unpublishProductOfferStorage(array $productOfferReferences): void;
}
