<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business\Availability;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
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
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityForRequest(
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        $productOfferStockTransfer = $this->getProductOfferStockTransfer($productOfferAvailabilityRequestTransfer);
        $availability = $this->calculateAvailabilityForRequest($productOfferStockTransfer, $productOfferAvailabilityRequestTransfer);

        return (new ProductConcreteAvailabilityTransfer())
            ->setAvailability($availability)
            ->setSku($productOfferAvailabilityRequestTransfer->getSku())
            ->setIsNeverOutOfStock($productOfferStockTransfer->getIsNeverOutOfStock());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateAvailabilityForRequest(
        ProductOfferStockTransfer $productOfferStockTransfer,
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): Decimal {
        $quantity = $productOfferStockTransfer->getQuantity();

        if ($quantity && $quantity->isZero()) {
            return $quantity;
        }

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReference())
            ->setStore($productOfferAvailabilityRequestTransfer->getStore())
            ->setSku($productOfferAvailabilityRequestTransfer->getSku());

        $reservationResponseTransfer = $this->omsFacade->getOmsReservedProductQuantity($reservationRequestTransfer);

        return $quantity->subtract($reservationResponseTransfer->getReservationQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    protected function getProductOfferStockTransfer(ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer): ProductOfferStockTransfer
    {
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReference())
            ->setStore($productOfferAvailabilityRequestTransfer->getStore());

        return $this->productOfferStockFacade->getProductOfferStock($productOfferStockRequestTransfer);
    }
}
