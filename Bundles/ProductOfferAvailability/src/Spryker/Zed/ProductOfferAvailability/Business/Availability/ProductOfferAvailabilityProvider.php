<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business\Availability;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockResultTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeInterface;

class ProductOfferAvailabilityProvider implements ProductOfferAvailabilityProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface
     */
    protected ProductOfferAvailabilityToOmsFacadeInterface $omsFacade;

    /**
     * @var \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeInterface
     */
    protected ProductOfferAvailabilityToProductOfferStockFacadeInterface $productOfferStockFacade;

    /**
     * @var \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferFacadeInterface
     */
    protected ProductOfferAvailabilityToProductOfferFacadeInterface $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeInterface $productOfferStockFacade
     * @param \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(
        ProductOfferAvailabilityToOmsFacadeInterface $omsFacade,
        ProductOfferAvailabilityToProductOfferStockFacadeInterface $productOfferStockFacade,
        ProductOfferAvailabilityToProductOfferFacadeInterface $productOfferFacade
    ) {
        $this->omsFacade = $omsFacade;
        $this->productOfferStockFacade = $productOfferStockFacade;
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        if (
            !$productOfferAvailabilityRequestTransfer->getProductOfferReference()
            || !$productOfferAvailabilityRequestTransfer->getStore()
            || !$productOfferAvailabilityRequestTransfer->getStoreOrFail()->getIdStore()
        ) {
            return null;
        }

        if (!$this->hasProductOfferForStore($productOfferAvailabilityRequestTransfer)) {
            return (new ProductConcreteAvailabilityTransfer())
                ->setAvailability(new Decimal(0))
                ->setSku($productOfferAvailabilityRequestTransfer->getSku())
                ->setIsNeverOutOfStock(false);
        }

        $productOfferStockResultTransfer = $this->findProductOfferStockResultTransfer($productOfferAvailabilityRequestTransfer);

        if (!$productOfferStockResultTransfer) {
            return null;
        }

        $availability = $this->calculateAvailabilityForRequest($productOfferStockResultTransfer, $productOfferAvailabilityRequestTransfer);

        return (new ProductConcreteAvailabilityTransfer())
            ->setAvailability($availability)
            ->setSku($productOfferAvailabilityRequestTransfer->getSku())
            ->setIsNeverOutOfStock($productOfferStockResultTransfer->getIsNeverOutOfStock());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockResultTransfer $productOfferStockResultTransfer
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateAvailabilityForRequest(
        ProductOfferStockResultTransfer $productOfferStockResultTransfer,
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): Decimal {
        $quantity = $productOfferStockResultTransfer->getQuantity();

        if ($quantity && $quantity->isZero()) {
            return $quantity;
        }

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReference())
            ->setStore($productOfferAvailabilityRequestTransfer->getStore())
            ->setSku($productOfferAvailabilityRequestTransfer->getSku());

        $reservationResponseTransfer = $this->omsFacade->getOmsReservedProductQuantity($reservationRequestTransfer);

        /** @phpstan-var \Spryker\DecimalObject\Decimal $reservationQuantity */
        $reservationQuantity = $reservationResponseTransfer->getReservationQuantity();

        /** @phpstan-var \Spryker\DecimalObject\Decimal $quantity */
        $availableQuantity = $quantity->subtract($reservationQuantity);

        return $availableQuantity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return bool
     */
    protected function hasProductOfferForStore(ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer): bool
    {
        $productOfferConditionsTransfer = (new ProductOfferConditionsTransfer())
            ->addProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReferenceOrFail())
            ->addIdStore($productOfferAvailabilityRequestTransfer->getStoreOrFail()->getIdStoreOrFail());

        $productOfferCollectionTransfer = $this->productOfferFacade->getProductOfferCollection(
            (new ProductOfferCriteriaTransfer())->setProductOfferConditions($productOfferConditionsTransfer),
        );

        return $productOfferCollectionTransfer->getProductOffers()->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockResultTransfer|null
     */
    protected function findProductOfferStockResultTransfer(
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ?ProductOfferStockResultTransfer {
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReference())
            ->setIsStockActive(true)
            ->setStore($productOfferAvailabilityRequestTransfer->getStore());

        $productOfferStockResultTransfer = $this->productOfferStockFacade->getProductOfferStockResult($productOfferStockRequestTransfer);

        if (!$productOfferStockResultTransfer->getQuantity() && !$productOfferStockResultTransfer->getIsNeverOutOfStock()) {
            return null;
        }

        return $productOfferStockResultTransfer;
    }
}
