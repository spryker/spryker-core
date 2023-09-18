<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantStockAddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeInterface;

class ItemExpander implements ItemExpanderInterface
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithMerchantStockAddressSplitByStockAvailability(OrderTransfer $orderTransfer): OrderTransfer
    {
        if (!$orderTransfer->getStore()) {
            return $orderTransfer;
        }

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $productOfferStockTransfers = $this->getProductOfferStocks($orderTransfer->getStore(), $itemTransfer);

            if (!$productOfferStockTransfers) {
                continue;
            }

            $merchantStockAddresses = $this->splitMerchantStockAddressByQuantityToShip(
                $orderTransfer->getStore(),
                $itemTransfer,
                $productOfferStockTransfers,
            );

            $itemTransfer->setMerchantStockAddresses($merchantStockAddresses);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expandCalculableObjectItemsWithMerchantStockAddressSplitByStockAvailability(
        CalculableObjectTransfer $quoteTransfer
    ): CalculableObjectTransfer {
        if (!$quoteTransfer->getStore() || !$quoteTransfer->getStore()->getName()) {
            return $quoteTransfer;
        }

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productOfferStockTransfers = $this->getProductOfferStocks($quoteTransfer->getStore()->getName(), $itemTransfer);

            if (!$productOfferStockTransfers) {
                continue;
            }

            $merchantStockAddresses = $this->splitMerchantStockAddressByQuantityToShip(
                $quoteTransfer->getStore()->getName(),
                $itemTransfer,
                $productOfferStockTransfers,
            );

            $itemTransfer->setMerchantStockAddresses($merchantStockAddresses);
        }

        return $quoteTransfer;
    }

    /**
     * @param string $storeName
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer> $productOfferStockTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MerchantStockAddressTransfer>
     */
    protected function splitMerchantStockAddressByQuantityToShip(
        string $storeName,
        ItemTransfer $itemTransfer,
        ArrayObject $productOfferStockTransfers
    ): ArrayObject {
        $merchantStockAddresses = new ArrayObject();
        $pendingQuantityToShip = new Decimal($itemTransfer->getQuantity() ?? 0);

        /** @var \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer */
        foreach ($productOfferStockTransfers as $productOfferStockTransfer) {
            if (!$productOfferStockTransfer->getStock()) {
                continue;
            }

            if ($pendingQuantityToShip->lessThanOrEquals(0)) {
                break;
            }

            $quantityToShip = $this->getQuantityToShipFromMerchantStock(
                $pendingQuantityToShip,
                $productOfferStockTransfer,
                $itemTransfer,
                $storeName,
            );

            if ($quantityToShip->lessThanOrEquals(0)) {
                continue;
            }

            $merchantStockAddresses->append(
                (new MerchantStockAddressTransfer())
                    ->setQuantityToShip($quantityToShip)
                    ->setStockAddress($productOfferStockTransfer->getStock()->getAddress()),
            );

            $pendingQuantityToShip = $pendingQuantityToShip->subtract($quantityToShip);
        }

        /*
         * If we couldn't ship the whole item quantity using merchant stocks
         * then we shouldn't set anything in the item
         */
        if ($pendingQuantityToShip->greaterThan(0)) {
            return new ArrayObject();
        }

        return $merchantStockAddresses;
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $pendingQuantityToShip
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string|null $storeName
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getQuantityToShipFromMerchantStock(
        Decimal $pendingQuantityToShip,
        ProductOfferStockTransfer $productOfferStockTransfer,
        ItemTransfer $itemTransfer,
        ?string $storeName
    ): Decimal {
        if ($productOfferStockTransfer->getIsNeverOutOfStock()) {
            return $pendingQuantityToShip;
        }

        $availability = $this->calculateProductOfferStockAvailability(
            $productOfferStockTransfer,
            $itemTransfer->getSku(),
            (new StoreTransfer())->setName($storeName),
        );

        if ($availability->lessThanOrEquals(0)) {
            return new Decimal(0);
        }

        if ($availability->lessThan($pendingQuantityToShip)) {
            return $availability;
        }

        return $pendingQuantityToShip;
    }

    /**
     * @param string $storeName
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>|null
     */
    protected function getProductOfferStocks(string $storeName, ItemTransfer $itemTransfer): ?ArrayObject
    {
        if (!$itemTransfer->getProductOfferReference()) {
            return null;
        }

        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference(
                $itemTransfer->getProductOfferReference(),
            )
            ->setStore(
                (new StoreTransfer())->setName($storeName),
            )
            ->setOrderByLargestStock(true);

        return $this->productOfferStockFacade->findProductOfferStocks($productOfferStockRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     * @param string|null $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateProductOfferStockAvailability(
        ProductOfferStockTransfer $productOfferStockTransfer,
        ?string $sku,
        StoreTransfer $storeTransfer
    ): Decimal {
        $quantity = $productOfferStockTransfer->getQuantity();

        if (!$quantity) {
            return new Decimal(0);
        }

        if ($quantity->isZero()) {
            return $quantity;
        }

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference($productOfferStockTransfer->getProductOfferReference())
            ->setSku($sku)
            ->setStore($storeTransfer);

        $reservationResponseTransfer = $this->omsFacade->getOmsReservedProductQuantity($reservationRequestTransfer);

        /** @var \Spryker\DecimalObject\Decimal $reservationQuantity */
        $reservationQuantity = $reservationResponseTransfer->getReservationQuantity();

        return $quantity->subtract($reservationQuantity);
    }
}
