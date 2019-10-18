<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Helper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface;

class AvailabilityHelper implements AvailabilityHelperInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface $availabilityQueryContainer
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface $stockFacade
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        AvailabilityGuiToAvailabilityQueryContainerInterface $availabilityQueryContainer,
        AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer,
        AvailabilityToStoreFacadeInterface $storeFacade,
        AvailabilityGuiToStockInterface $stockFacade,
        AvailabilityGuiToOmsFacadeInterface $omsFacade
    ) {
        $this->availabilityQueryContainer = $availabilityQueryContainer;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->storeFacade = $storeFacade;
        $this->stockFacade = $stockFacade;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailabilityTransfer(int $idProductAbstract, int $idLocale, int $idStore): ?ProductAbstractAvailabilityTransfer
    {
        $storeTransfer = $this->storeFacade->getStoreById($idStore);
        $stockTypes = $this->stockFacade->getStoreToWarehouseMapping()[$storeTransfer->getName()];

        $productAbstractAvailabilityEntity = $this->availabilityQueryContainer
            ->queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale, $idStore, $stockTypes)
            ->findOne();

        if ($productAbstractAvailabilityEntity === null) {
            return null;
        }

        return (new ProductAbstractAvailabilityTransfer())
            ->setProductName($productAbstractAvailabilityEntity->getVirtualColumn(static::PRODUCT_NAME))
            ->setSku($productAbstractAvailabilityEntity->getSku())
            ->setAvailability((new Decimal($productAbstractAvailabilityEntity->getVirtualColumn(static::AVAILABILITY_QUANTITY) ?? 0))->trim())
            ->setIsNeverOutOfStock(stripos($productAbstractAvailabilityEntity->getVirtualColumn(static::CONCRETE_NEVER_OUT_OF_STOCK_SET), 'true') !== false)
            ->setStockQuantity((new Decimal($productAbstractAvailabilityEntity->getVirtualColumn(static::STOCK_QUANTITY) ?? 0))->trim())
            ->setReservationQuantity(
                $this->calculateReservation($productAbstractAvailabilityEntity->getVirtualColumn(static::RESERVATION_QUANTITY) ?? '', $storeTransfer)->trim()
            );
    }

    /**
     * @param string $neverOutOfStockSet
     *
     * @return bool
     */
    public function isNeverOutOfStock(string $neverOutOfStockSet): bool
    {
        return stripos($neverOutOfStockSet, 'true') !== false;
    }

    /**
     * @param string $reservationAggregationSet
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateReservation(string $reservationAggregationSet, StoreTransfer $storeTransfer): Decimal
    {
        $reservation = new Decimal(0);
        $reservationItems = array_unique(explode(',', $reservationAggregationSet));
        foreach ($reservationItems as $item) {
            $itemParts = array_filter(explode(':', $item));
            if (!isset($itemParts[0])) {
                continue;
            }

            $concreteProductReservation = new Decimal($itemParts[1] ?? 0);
            $concreteProductReservation = $this->sumReservationsFromOtherStores($itemParts[0], $storeTransfer, $concreteProductReservation);
            $reservation = $reservation->add($concreteProductReservation);
        }

        return $reservation;
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Spryker\DecimalObject\Decimal $currentStoreReservationQuantity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function sumReservationsFromOtherStores(string $concreteSku, StoreTransfer $storeTransfer, Decimal $currentStoreReservationQuantity): Decimal
    {
        return $currentStoreReservationQuantity->add(
            $this->omsFacade->getReservationsFromOtherStores($concreteSku, $storeTransfer)
        );
    }

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isBundleProduct(int $idProductConcrete): bool
    {
        return $this->productBundleQueryContainer->queryBundleProduct($idProductConcrete)->exists();
    }

    /**
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdLocale(int $idLocale, int $idStore)
    {
        return $this->availabilityQueryContainer->queryAvailabilityAbstractWithStockByIdLocale(
            $idLocale,
            $idStore,
            $this->getStockWarehousesForStore($idStore)
        );
    }

    /**
     * @param int|null $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdProductAbstractAndIdLocale(?int $idProductAbstract, int $idLocale, int $idStore)
    {
        return $this->availabilityQueryContainer->queryAvailabilityWithStockByIdProductAbstractAndIdLocale(
            $idProductAbstract,
            $idLocale,
            $idStore,
            $this->getStockWarehousesForStore($idStore)
        );
    }

    /**
     * @param int $idStore
     *
     * @return string[]
     */
    protected function getStockWarehousesForStore(int $idStore): array
    {
        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        return $this->stockFacade->getStoreToWarehouseMapping()[$storeTransfer->getName()] ?? [];
    }
}
