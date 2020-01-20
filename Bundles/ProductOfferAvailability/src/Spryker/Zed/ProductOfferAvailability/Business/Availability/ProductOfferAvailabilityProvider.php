<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business\Availability;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeInterface;

class ProductOfferAvailabilityProvider implements ProductOfferAvailabilityProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeInterface
     */
    protected $productOfferStockFacade;

    /**
     * @param \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeInterface $productOfferStockFacade
     */
    public function __construct(
        ProductOfferAvailabilityToOmsFacadeInterface $omsFacade,
        ProductOfferAvailabilityToProductOfferStockFacadeInterface $productOfferStockFacade
    ) {
        $this->omsFacade = $omsFacade;
        $this->productOfferStockFacade = $productOfferStockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return bool
     */
    public function isProductSellableForRequest(ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer): bool
    {
        return $productOfferAvailabilityRequestTransfer->getQuantity()
            ->lessThanOrEquals(
                $this->calculateAvailabilityForRequest($productOfferAvailabilityRequestTransfer)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityForRequest(ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer): ?ProductConcreteAvailabilityTransfer
    {
        $availability = $this->calculateAvailabilityForRequest($productOfferAvailabilityRequestTransfer);

        return (new ProductConcreteAvailabilityTransfer())
            ->setAvailability($availability)
            ->setSku($productOfferAvailabilityRequestTransfer->getSku())
            ->setIsNeverOutOfStock(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateAvailabilityForRequest(ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer): Decimal
    {
        $reservedProductQuantity = $this->omsFacade->getOmsReservedProductQuantityForSku(
            $productOfferAvailabilityRequestTransfer->getSku(),
            $productOfferAvailabilityRequestTransfer->getStore()
        );

        $stock = $this->productOfferStockFacade->getProductOfferStock(
            (new ProductOfferStockRequestTransfer())
                ->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReference())
                ->setStore($productOfferAvailabilityRequestTransfer->getStore())
        );

        if ($stock->isZero()) {
            return $stock;
        }

        return $stock->subtract($reservedProductQuantity);
    }
}
