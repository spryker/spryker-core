<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Plugin;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferReferenceStrategyPluginInterface;

class MerchantProductProductOfferReferenceStrategyPlugin implements ProductOfferReferenceStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if ProductOfferStorageCriteria.merchantReference is not null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): bool
    {
        return (bool)$productOfferStorageCriteriaTransfer->getMerchantReference();
    }

    /**
     * {@inheritDoc}
     * - Returns null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string|null
     */
    public function findProductOfferReference(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ?string
    {
        return null;
    }
}
