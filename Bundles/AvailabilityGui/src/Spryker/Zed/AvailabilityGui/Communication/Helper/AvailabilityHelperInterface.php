<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Helper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface AvailabilityHelperInterface
{
    /**
     * @uses \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET
     */
    public const CONCRETE_NEVER_OUT_OF_STOCK_SET = 'concreteNeverOutOfStockSet';

    /**
     * @uses \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer::RESERVATION_QUANTITY
     */
    public const RESERVATION_QUANTITY = 'reservationQuantity';

    /**
     * @uses \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer::STOCK_QUANTITY
     */
    public const STOCK_QUANTITY = 'stockQuantity';

    /**
     * @uses \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer::AVAILABILITY_QUANTITY
     */
    public const AVAILABILITY_QUANTITY = 'availabilityQuantity';

    /**
     * @uses \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer::PRODUCT_NAME
     */
    public const PRODUCT_NAME = 'productName';

    /**
     * @uses \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer::CONCRETE_SKU
     */
    public const CONCRETE_SKU = 'concreteSku';

    /**
     * @uses \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer::CONCRETE_AVAILABILITY
     */
    public const CONCRETE_AVAILABILITY = 'concreteAvailability';

    /**
     * @uses \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer::CONCRETE_NAME
     */
    public const CONCRETE_NAME = 'concreteName';

    /**
     * @uses \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer::ID_PRODUCT
     */
    public const ID_PRODUCT = 'idProduct';

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailabilityTransfer(int $idProductAbstract, int $idLocale, int $idStore): ?ProductAbstractAvailabilityTransfer;

    /**
     * @param string $neverOutOfStockSet
     *
     * @return bool
     */
    public function isNeverOutOfStock(string $neverOutOfStockSet): bool;

    /**
     * @param string $reservationAggregationSet
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateReservation(string $reservationAggregationSet, StoreTransfer $storeTransfer): Decimal;

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Spryker\DecimalObject\Decimal $currentStoreReservationQuantity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function sumReservationsFromOtherStores(string $concreteSku, StoreTransfer $storeTransfer, Decimal $currentStoreReservationQuantity): Decimal;

    /**
     * @param int $idProduct
     *
     * @return bool
     */
    public function isBundleProduct(int $idProduct): bool;

    /**
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdLocale(int $idLocale, int $idStore);

    /**
     * @param int|null $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdProductAbstractAndIdLocale(?int $idProductAbstract, int $idLocale, int $idStore);
}
