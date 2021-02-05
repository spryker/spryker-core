<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Plugin\AvailabilityStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityStorageStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageFactory getFactory()
 * @method \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageClientInterface getClient()
 */
class ProductOfferAvailabilityStorageStrategyPlugin extends AbstractPlugin implements AvailabilityStorageStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductViewTransfer $productViewTransfer): bool
    {
        return (bool)$productViewTransfer->getProductOfferReference();
    }

    /**
     * {@inheritDoc}
     * - Returns true if product offer available by current store and provided ProductViewTransfer.productOfferReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductAvailable(ProductViewTransfer $productViewTransfer): bool
    {
        $storeTransfer = $this->getFactory()
            ->getStoreClient()
            ->getCurrentStore();

        $productOfferAvailabilityStorageTransfer = $this->getClient()
            ->findByProductOfferReference(
                $productViewTransfer->getProductOfferReference(),
                $storeTransfer->getName()
            );

        return $productOfferAvailabilityStorageTransfer && $productOfferAvailabilityStorageTransfer->getIsAvailable();
    }
}
