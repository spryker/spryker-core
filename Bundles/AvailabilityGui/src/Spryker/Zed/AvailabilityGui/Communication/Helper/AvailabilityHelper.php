<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Helper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface;
use Spryker\Zed\AvailabilityGui\Dependency\Service\AvailabilityGuiToAvailabilityServiceInterface;

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
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Service\AvailabilityGuiToAvailabilityServiceInterface
     */
    protected $availabilityService;

    /**
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface $availabilityQueryContainer
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface $stockFacade
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Service\AvailabilityGuiToAvailabilityServiceInterface $availabilityService
     */
    public function __construct(
        AvailabilityGuiToAvailabilityQueryContainerInterface $availabilityQueryContainer,
        AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer,
        AvailabilityToStoreFacadeInterface $storeFacade,
        AvailabilityGuiToStockInterface $stockFacade,
        AvailabilityGuiToOmsFacadeInterface $omsFacade,
        AvailabilityGuiToAvailabilityServiceInterface $availabilityService
    ) {
        $this->availabilityQueryContainer = $availabilityQueryContainer;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->storeFacade = $storeFacade;
        $this->stockFacade = $stockFacade;
        $this->omsFacade = $omsFacade;
        $this->availabilityService = $availabilityService;
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
        $stockNames = $this->getStockNamesForStore($storeTransfer);

        $productAbstractAvailabilityEntity = $this->availabilityQueryContainer
            ->queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale, $idStore, $stockNames)
            ->findOne();

        if ($productAbstractAvailabilityEntity === null) {
            return null;
        }

        $neverOutOfStockSet = '';
        $stockQuantity = 0;
        $reservationQuantity = '';

        if ($productAbstractAvailabilityEntity->hasVirtualColumn(AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET)) {
            $neverOutOfStockSet = $productAbstractAvailabilityEntity->getVirtualColumn(static::CONCRETE_NEVER_OUT_OF_STOCK_SET) ?? '';
        }

        if ($productAbstractAvailabilityEntity->hasVirtualColumn(static::STOCK_QUANTITY)) {
            $stockQuantity = $productAbstractAvailabilityEntity->getVirtualColumn(static::STOCK_QUANTITY) ?? 0;
        }

        if ($productAbstractAvailabilityEntity->hasVirtualColumn(static::RESERVATION_QUANTITY)) {
            $reservationQuantity = $productAbstractAvailabilityEntity->getVirtualColumn(static::RESERVATION_QUANTITY) ?? '';
        }

        return (new ProductAbstractAvailabilityTransfer())
            ->setProductName($productAbstractAvailabilityEntity->getVirtualColumn(static::PRODUCT_NAME))
            ->setSku($productAbstractAvailabilityEntity->getSku())
            ->setAvailability((new Decimal($productAbstractAvailabilityEntity->getVirtualColumn(static::AVAILABILITY_QUANTITY) ?? 0))->trim())
            ->setIsNeverOutOfStock($this->isNeverOutOfStock($neverOutOfStockSet))
            ->setStockQuantity((new Decimal($stockQuantity))->trim())
            ->setReservationQuantity($this->calculateReservation($reservationQuantity, $storeTransfer)->trim());
    }

    /**
     * @param string|null $neverOutOfStockSet
     *
     * @return bool
     */
    public function isNeverOutOfStock(?string $neverOutOfStockSet): bool
    {
        if ($neverOutOfStockSet === null) {
            return false;
        }

        return $this->availabilityService->isAbstractProductNeverOutOfStock($neverOutOfStockSet);
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
            if ((int)strpos($item, ':') === 0) {
                continue;
            }

            [$sku, $quantity] = explode(':', $item);
            if ($sku === '' || !is_numeric($quantity)) {
                continue;
            }

            $concreteProductReservation = new Decimal($quantity);
            $concreteProductReservation = $this->sumReservationsFromOtherStores($sku, $storeTransfer, $concreteProductReservation);
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
            $this->omsFacade->getReservationsFromOtherStores($concreteSku, $storeTransfer),
        );
    }

    /**
     * @param int $idProduct
     *
     * @return bool
     */
    public function isBundleProduct(int $idProduct): bool
    {
        return $this->productBundleQueryContainer->queryBundleProduct($idProduct)->exists();
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
            $this->getStockNamesForStoreByStoreId($idStore),
        );
    }

    /**
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithCurrentStockAndReservedProductsAggregated(
        int $idLocale,
        int $idStore
    ): SpyProductAbstractQuery {
        $stockIds = $this->getStockIdsByIdStore($idStore);

        return $this->availabilityQueryContainer->queryAvailabilityAbstractWithCurrentStockAndReservedProductsAggregated(
            $idLocale,
            $idStore,
            $stockIds,
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
            $this->getStockNamesForStoreByStoreId($idStore),
        );
    }

    /**
     * @param array<int> $productAbstractIds
     * @param int $idLocale
     * @param int $idStore
     *
     * @return array<\Orm\Zed\Product\Persistence\SpyProductAbstract>
     */
    public function getProductAbstractEntitiesWithStockByProductAbstractIds(
        array $productAbstractIds,
        int $idLocale,
        int $idStore
    ): array {
        $availabilityAbstractEntities = $this->availabilityQueryContainer
            ->queryProductAbstractWithStockByProductAbstractIds(
                $productAbstractIds,
                $idLocale,
                $idStore,
                $this->getStockNamesForStoreByStoreId($idStore),
            )->find();

        return $this->orderAvailabilityAbstractEntitiesByProductAbstractIdsSequence(
            $availabilityAbstractEntities,
            $productAbstractIds,
        );
    }

    /**
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdStore(int $idStore): SpyAvailabilityAbstractQuery
    {
        return $this->availabilityQueryContainer->queryAvailabilityAbstractByIdStore($idStore);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProductAbstract[] $availabilityAbstractEntities
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\Product\Persistence\SpyProductAbstract>
     */
    protected function orderAvailabilityAbstractEntitiesByProductAbstractIdsSequence(
        ObjectCollection $availabilityAbstractEntities,
        array $productAbstractIds
    ): array {
        $orderedAvailabilityAbstractEntities = [];
        foreach ($availabilityAbstractEntities as $availabilityAbstractEntity) {
            $indexProductAbstract = array_search($availabilityAbstractEntity->getIdProductAbstract(), $productAbstractIds, true);
            if ($indexProductAbstract === false) {
                continue;
            }

            $orderedAvailabilityAbstractEntities[$indexProductAbstract] = $availabilityAbstractEntity;
        }

        ksort($orderedAvailabilityAbstractEntities);

        return $orderedAvailabilityAbstractEntities;
    }

    /**
     * @param int $idStore
     *
     * @return array<string>
     */
    protected function getStockNamesForStoreByStoreId(int $idStore): array
    {
        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        return $this->getStockNamesForStore($storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<string>
     */
    protected function getStockNamesForStore(StoreTransfer $storeTransfer): array
    {
        $stockTransfers = $this->stockFacade->getAvailableWarehousesForStore($storeTransfer);

        return array_map(function (StockTransfer $stockTransfer): string {
            return $stockTransfer->getName();
        }, $stockTransfers);
    }

    /**
     * @param int $idStore
     *
     * @return array<int>
     */
    protected function getStockIdsByIdStore(int $idStore): array
    {
        $storeTransfer = $this->storeFacade->getStoreById($idStore);
        $stockTransfers = $this->stockFacade->getAvailableWarehousesForStore($storeTransfer);

        return $this->getStockIdsByStockTransfers($stockTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\StockTransfer> $stockTransfers
     *
     * @return array<int>
     */
    protected function getStockIdsByStockTransfers(array $stockTransfers): array
    {
        $stockIds = [];
        foreach ($stockTransfers as $stockTransfer) {
            $stockIds[] = $stockTransfer->getIdStock();
        }

        return $stockIds;
    }
}
