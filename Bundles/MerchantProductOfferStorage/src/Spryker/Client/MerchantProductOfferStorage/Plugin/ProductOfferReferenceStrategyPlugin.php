<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Plugin;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferReferenceStrategyPluginInterface;

/**
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface getClient()
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageFactory getFactory()
 */
class ProductOfferReferenceStrategyPlugin extends AbstractPlugin implements ProductOfferReferenceStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if ProductOfferStorageCriteria.productOfferReference is not null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): bool
    {
        return (bool)$productOfferStorageCriteriaTransfer->getProductOfferReference();
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
        $productOfferStorageCriteriaTransfer = clone $productOfferStorageCriteriaTransfer;
        $productOfferStorageCriteriaTransfer->setMerchantReference(null);

        $productOfferStorageTransfers = $this->getFactory()
            ->createProductOfferStorageReader()
            ->getProductOffersBySkus($productOfferStorageCriteriaTransfer)
            ->getProductOffersStorage()
            ->getArrayCopy();

        if (!$productOfferStorageTransfers) {
            return null;
        }

        $productOfferReferences = array_map(
            function (ProductOfferStorageTransfer $productOfferStorageTransfer) {
                return $productOfferStorageTransfer->getProductOfferReference();
            },
            $productOfferStorageTransfers
        );

        if (
            $productOfferStorageCriteriaTransfer->getProductOfferReference()
            && in_array($productOfferStorageCriteriaTransfer->getProductOfferReference(), $productOfferReferences)
        ) {
            return $productOfferStorageCriteriaTransfer->getProductOfferReference();
        }

        return null;
    }
}
