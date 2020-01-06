<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Communication\Plugin\Availability;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOfferAvailability\Business\ProductOfferAvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferAvailability\ProductOfferAvailabilityConfig getConfig()
 */
class ProductOfferAvailabilityStrategyPlugin extends AbstractPlugin implements AvailabilityStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if ProductAvailabilityCriteriaTransfer contains product offer reference.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(
        string $sku,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): bool {
        return $productAvailabilityCriteriaTransfer && $productAvailabilityCriteriaTransfer->getProductOfferReference();
    }

    /**
     * {@inheritDoc}
     * - Returns product concrete availability for product offer.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityForStore(
        string $sku,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): ?ProductConcreteAvailabilityTransfer {
        if (!$productAvailabilityCriteriaTransfer) {
            return null;
        }

        $productAvailabilityCriteriaTransfer->requireProductOfferReference();

        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setSku($sku)
            ->setStore($storeTransfer)
            ->setProductOfferReference($productAvailabilityCriteriaTransfer->getProductOfferReference());

        return $this->getFacade()
            ->findProductConcreteAvailabilityForRequest($productOfferAvailabilityRequestTransfer);
    }
}
