<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferStorage\Plugin\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferReferenceStrategyPluginInterface;

/**
 * @method \Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface getClient()
 * @method \Spryker\Client\ProductOfferStorage\ProductOfferStorageFactory getFactory()
 */
class DefaultProductOfferReferenceStrategyPlugin extends AbstractPlugin implements ProductOfferReferenceStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if ProductOfferStorageCriteria.productOfferReference is null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): bool
    {
        return !$productOfferStorageCriteriaTransfer->getProductOfferReference();
    }

    /**
     * {@inheritDoc}
     * - Returns first product offer reference from collection by provided ProductOfferStorageCriteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string|null
     */
    public function findProductOfferReference(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ?string
    {
        return $this->getFactory()
            ->createProductOfferStorageReader()
            ->findDefaultProductOfferReference($productOfferStorageCriteriaTransfer);
    }
}
