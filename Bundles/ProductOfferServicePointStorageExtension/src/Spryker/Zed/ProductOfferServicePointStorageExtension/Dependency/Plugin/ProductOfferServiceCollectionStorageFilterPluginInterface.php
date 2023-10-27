<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorageExtension\Dependency\Plugin;

/**
 * Provides ability to filter product offer services collection by provided criteria.
 * This plugin stack gets executed after a list of `ProductOfferServicesTransfer` for publishing is retrieved from Persistence.
 */
interface ProductOfferServiceCollectionStorageFilterPluginInterface
{
    /**
     * Specification:
     * - Filters product offer services collection.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>
     */
    public function filterProductOfferServices(array $productOfferServicesTransfers): array;
}
