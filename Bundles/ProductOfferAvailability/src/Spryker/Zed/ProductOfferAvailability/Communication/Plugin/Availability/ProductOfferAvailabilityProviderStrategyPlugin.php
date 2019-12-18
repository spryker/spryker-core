<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Communication\Plugin\Availability;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityProviderStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOfferAvailability\Business\ProductOfferAvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferAvailability\ProductOfferAvailabilityConfig getConfig()
 */
class ProductOfferAvailabilityProviderStrategyPlugin extends AbstractPlugin implements AvailabilityProviderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if criteria transfer contains product offer reference.
     *
     * @api
     *
     * @param string $concreteSku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(
        string $concreteSku,
        Decimal $quantity,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
    ): bool {
        return $productAvailabilityCriteriaTransfer
            && $productAvailabilityCriteriaTransfer->getProductOffer()
            && $productAvailabilityCriteriaTransfer->getProductOffer()->getProductOfferReference();
    }

    /**
     * {@inheritDoc}
     * - Returns true if product offer is available in requested quantity.
     *
     * @api
     *
     * @param string $concreteSku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore(
        string $concreteSku,
        Decimal $quantity,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
    ): bool {
        $productAvailabilityCriteriaTransfer
            ->requireProductOffer()
            ->getProductOffer()
            ->requireProductOfferReference();

        $productOfferAvailabilityRequestTransfer = (new ProductOfferAvailabilityRequestTransfer())
            ->setSku($concreteSku)
            ->setQuantity($quantity)
            ->setProductOfferReference($productAvailabilityCriteriaTransfer->getProductOffer()->getProductOfferReference())
            ->setStore($storeTransfer);

        return $this->getFacade()
            ->isProductSellableForRequest($productOfferAvailabilityRequestTransfer);
    }
}
