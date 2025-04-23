<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductAvailabilityDataTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Product\Persistence\SpyProduct;
use Propel\Runtime\Collection\Collection;
use Spryker\DecimalObject\Decimal;
use Spryker\Service\Availability\AvailabilityServiceInterface;
use Spryker\Zed\Availability\Persistence\Propel\Mapper\StoreMapper;

class AvailabilityMapper implements AvailabilityMapperInterface
{
    /**
     * @var \Spryker\Service\Availability\AvailabilityServiceInterface
     */
    protected $availabilityService;

    /**
     * @var \Spryker\Zed\Availability\Persistence\Propel\Mapper\StoreMapper
     */
    protected StoreMapper $storeMapper;

    /**
     * @param \Spryker\Service\Availability\AvailabilityServiceInterface $availabilityService
     * @param \Spryker\Zed\Availability\Persistence\Propel\Mapper\StoreMapper $storeMapper
     */
    public function __construct(
        AvailabilityServiceInterface $availabilityService,
        StoreMapper $storeMapper
    ) {
        $this->availabilityService = $availabilityService;
        $this->storeMapper = $storeMapper;
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
        SpyAvailability $availabilityEntity,
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): ProductConcreteAvailabilityTransfer {
        return $productConcreteAvailabilityTransfer
            ->setSku($availabilityEntity->getSku())
            ->setAvailability($availabilityEntity->getQuantity())
            ->setIsNeverOutOfStock($availabilityEntity->getIsNeverOutOfStock());
    }

    /**
     * @param array $availabilityAbstractEntityArray
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function mapAvailabilityEntityToProductAbstractAvailabilityTransfer(
        array $availabilityAbstractEntityArray,
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
    ): ProductAbstractAvailabilityTransfer {
        $availabilityAbstractEntityArray = $this->processAvailabilityAbstractEntityArray($availabilityAbstractEntityArray);

        return $productAbstractAvailabilityTransfer
            ->fromArray($availabilityAbstractEntityArray, true);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Availability\Persistence\SpyAvailability> $availabilityEntities
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer $productConcreteAvailabilityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer
     */
    public function mapAvailabilityEntitiesToProductConcreteAvailabilityCollectionTransfer(
        Collection $availabilityEntities,
        ProductConcreteAvailabilityCollectionTransfer $productConcreteAvailabilityCollectionTransfer
    ): ProductConcreteAvailabilityCollectionTransfer {
        foreach ($availabilityEntities as $availabilityEntity) {
            $productConcreteAvailabilityCollectionTransfer->addProductConcreteAvailability(
                $this->mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
                    $availabilityEntity,
                    new ProductConcreteAvailabilityTransfer(),
                ),
            );
        }

        return $productConcreteAvailabilityCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $availabilityEntities
     * @param \Orm\Zed\Product\Persistence\SpyProduct|null $productConcreteEntity
     * @param \Generated\Shared\Transfer\ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAvailabilityDataTransfer
     */
    public function mapAvailabilityEntitiesAndProductConcreteEntityToProductAvailabilityDataTransfer(
        Collection $availabilityEntities,
        ?SpyProduct $productConcreteEntity,
        ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
    ): ProductAvailabilityDataTransfer {
        if (!$productConcreteEntity) {
            return $productAvailabilityDataTransfer;
        }
        $productConcreteTransfer = (new ProductConcreteTransfer())->fromArray($productConcreteEntity->toArray(), true);
        $productAbstractTransfer = (new ProductAbstractTransfer())->fromArray($productConcreteEntity->getSpyProductAbstract()->toArray(), true);

        $productAvailabilityDataTransfer
            ->setProductAbstract($productAbstractTransfer)
            ->setProductConcrete($productConcreteTransfer);

        $this->expandProductAvailabilityDataTransferWithStock($productConcreteEntity, $productAvailabilityDataTransfer);

        /** @var \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity */
        foreach ($availabilityEntities as $availabilityEntity) {
            $this->extendProductAvailabilityDataTransferWithConcreteAvailability($availabilityEntity, $productAvailabilityDataTransfer);
            $this->extendProductAvailabilityDataTransferWithAbstractAvailability($availabilityEntity, $productAvailabilityDataTransfer);
        }

        return $productAvailabilityDataTransfer;
    }

    /**
     * @param array $availabilityAbstractData
     *
     * @return array
     */
    protected function processAvailabilityAbstractEntityArray(array $availabilityAbstractData): array
    {
        if (array_key_exists(ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY, $availabilityAbstractData)) {
            $availabilityAbstractData[ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY] = $this->calculateReservation(
                $availabilityAbstractData[ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY] ?? '',
            );
        }

        if (isset($availabilityAbstractData[ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK])) {
            $availabilityAbstractData[ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK] = $this->availabilityService->isAbstractProductNeverOutOfStock($availabilityAbstractData[ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK]);
        }

        if (
            isset($availabilityAbstractData[ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY]) &&
            isset($availabilityAbstractData[ProductAbstractAvailabilityTransfer::STOCK_QUANTITY])
        ) {
            $availabilityAbstractData[ProductAbstractAvailabilityTransfer::AVAILABILITY] = (new Decimal($availabilityAbstractData[ProductAbstractAvailabilityTransfer::STOCK_QUANTITY]))
                ->subtract($availabilityAbstractData[ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY]);
        }

        return $availabilityAbstractData;
    }

    /**
     * @param string $reservationAggregationSet
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateReservation(string $reservationAggregationSet): Decimal
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

            $reservation = $reservation->add(new Decimal($quantity));
        }

        return $reservation;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
     * @param \Generated\Shared\Transfer\ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
     *
     * @return void
     */
    protected function expandProductAvailabilityDataTransferWithStock(
        SpyProduct $productConcreteEntity,
        ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
    ): void {
        foreach ($productConcreteEntity->getStockProducts() as $stockProductEntity) {
            $productAvailabilityDataTransfer->addStockProduct(
                (new StockProductTransfer())->fromArray($stockProductEntity->toArray(), true),
            );

            $stockEntity = $stockProductEntity->getStock();
            $stockTransfer = (new StockTransfer())->fromArray($stockEntity->toArray(), true);
            $storeRelationTransfer = new StoreRelationTransfer();
            foreach ($stockEntity->getStockStores() as $stockStoreEntity) {
                $storeRelationTransfer->addStores(
                    (new StoreTransfer())->fromArray($stockStoreEntity->getStore()->toArray(), true),
                );
            }

            $stockTransfer->setStoreRelation($storeRelationTransfer);
            $productAvailabilityDataTransfer->addStock($stockTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity
     * @param \Generated\Shared\Transfer\ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
     *
     * @return void
     */
    protected function extendProductAvailabilityDataTransferWithConcreteAvailability(
        SpyAvailability $availabilityEntity,
        ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
    ): void {
        $productAvailabilityDataTransfer->addProductConcreteAvailability(
            (new ProductConcreteAvailabilityTransfer())
                ->fromArray($availabilityEntity->toArray(), true)
                ->setStore((new StoreTransfer())->fromArray($availabilityEntity->getStore()?->toArray() ?? [], true))
                ->setAvailability($availabilityEntity->getQuantity()),
        );
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity
     * @param \Generated\Shared\Transfer\ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
     *
     * @return void
     */
    protected function extendProductAvailabilityDataTransferWithAbstractAvailability(
        SpyAvailability $availabilityEntity,
        ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
    ): void {
        $abstractAvailability = $availabilityEntity->getSpyAvailabilityAbstract();
        $productAbstractAvailabilityTransfer = (new ProductAbstractAvailabilityTransfer())
            ->setAvailability($abstractAvailability->getQuantity())
            ->setSku($abstractAvailability->getAbstractSku())
            ->setIdStore($abstractAvailability->getFkStore());

        $productAvailabilityDataTransfer->addProductAbstractAvailability(
            $productAbstractAvailabilityTransfer,
        );
    }
}
