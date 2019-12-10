<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Communication\Plugin\AvailabilityStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\PostProductViewAvailabilityStorageExpandPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageFactory getFactory()
 * @method \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageClientInterface getClient()
 */
class ProductOfferPostProductViewAvailabilityStorageExpandPlugin extends AbstractPlugin implements PostProductViewAvailabilityStorageExpandPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductViewTransfer with product offer availability.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function postExpand(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        if (!$productViewTransfer->getProductOfferReference()) {
            return $productViewTransfer;
        }

        $storeTransfer = $this->getFactory()
            ->getStoreClient()
            ->getCurrentStore();

        $availabilityStorageTransfer = $this->getClient()
            ->findAvailabilityByProductOfferReference($productViewTransfer->getProductOfferReference(), $storeTransfer->getName());

        $productViewTransfer->setAvailable($availabilityStorageTransfer ? $availabilityStorageTransfer->getAvailability()->isPositive() : false);

        return $productViewTransfer;
    }
}
