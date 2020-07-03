<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;

/**
 * Provides ability to find product offer reference by provided ProductOfferStorageCriteria transfer object.
 */
interface ProductOfferReferenceStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin should be executed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): bool;

    /**
     * Specification:
     * - Returns product offer reference by provided ProductOfferStorageCriteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string|null
     */
    public function findProductOfferReference(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ?string;
}
