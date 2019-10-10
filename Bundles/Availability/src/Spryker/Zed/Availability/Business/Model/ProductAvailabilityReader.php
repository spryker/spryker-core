<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

class ProductAvailabilityReader implements ProductAvailabilityReaderInterface
{
    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface
     */
    protected $availabilityRepository;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface $availabilityQueryContainer
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        AvailabilityQueryContainerInterface $availabilityQueryContainer,
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityToStockInterface $stockFacade,
        AvailabilityToStoreFacadeInterface $storeFacade
    ) {
        $this->availabilityQueryContainer = $availabilityQueryContainer;
        $this->availabilityRepository = $availabilityRepository;
        $this->stockFacade = $stockFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function getProductAbstractAvailability(int $idProductAbstract, int $idLocale): ProductAbstractAvailabilityTransfer
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $stockNames = $this->stockFacade->getStoreToWarehouseMapping()[$storeTransfer->getName()];

        $productAbstractEntity = $this->availabilityQueryContainer
            ->queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale(
                $idProductAbstract,
                $idLocale,
                $storeTransfer->getIdStore(),
                $stockNames
            )
            ->findOne();

        return $this->mapAbstractProductAvailabilityEntityToTransfer($productAbstractEntity);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailability(int $idProductAbstract, int $idLocale, int $idStore): ?ProductAbstractAvailabilityTransfer
    {
        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        $stockTypes = $this->stockFacade->getStoreToWarehouseMapping()[$storeTransfer->getName()];

        $productAbstractEntity = $this->availabilityQueryContainer
            ->queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale(
                $idProductAbstract,
                $idLocale,
                $storeTransfer->getIdStore(),
                $stockTypes
            )
            ->findOne();

        if (!$productAbstractEntity) {
            return null;
        }

        return $this->mapAbstractProductAvailabilityEntityToTransfer($productAbstractEntity);
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailabilityForStore(int $idProductAbstract, StoreTransfer $storeTransfer): ?ProductAbstractAvailabilityTransfer
    {
        $productAbstractAvailabilityTransfer = $this->availabilityRepository
            ->findProductAbstractAvailabilityByIdProductAbstractAndStore(
                $idProductAbstract,
                $storeTransfer
            );

        if ($productAbstractAvailabilityTransfer === null) {
            return null;
        }

        return $productAbstractAvailabilityTransfer;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityForStore(int $idProductConcrete, StoreTransfer $storeTransfer): ?ProductConcreteAvailabilityTransfer
    {
        $productConcreteAvailabilityTransfer = $this->availabilityRepository
            ->findProductConcreteAvailabilityByIdProductConcreteAndStore(
                $idProductConcrete,
                $storeTransfer
            );

        if ($productConcreteAvailabilityTransfer === null) {
            return null;
        }

        return $productConcreteAvailabilityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(
        ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        $productConcreteAvailabilityRequestTransfer->requireSku();

        $storeTransfer = $this->storeFacade->getCurrentStore();

        $availabilityEntity = $this->availabilityQueryContainer
            ->queryAvailabilityBySkuAndIdStore($productConcreteAvailabilityRequestTransfer->getSku(), $storeTransfer->getIdStore())
            ->findOne();

        if ($availabilityEntity === null) {
            return null;
        }

        return $this->mapProductConcreteAvailabilityEntityToTransfer($availabilityEntity);
    }

    /**
     * @param string $reservationQuantity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateReservation(string $reservationQuantity): Decimal
    {
        $reservationItems = explode(',', $reservationQuantity);
        $reservationItems = array_unique($reservationItems);

        return $this->getReservationUniqueValue($reservationItems);
    }

    /**
     * @param string[] $reservationItems
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getReservationUniqueValue(array $reservationItems): Decimal
    {
        $reservation = new Decimal(0);
        foreach ($reservationItems as $item) {
            if (strpos($item, ':') === false) {
                continue;
            }

            [$sku, $value] = explode(':', $item);

            if (is_numeric($value)) {
                $reservation = $reservation->add($value);
            }
        }

        return $reservation;
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    protected function mapProductConcreteAvailabilityEntityToTransfer(SpyAvailability $availabilityEntity)
    {
        return (new ProductConcreteAvailabilityTransfer())
            ->setAvailability($availabilityEntity->getQuantity())
            ->setIsNeverOutOfStock($availabilityEntity->getIsNeverOutOfStock());
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    protected function mapAbstractProductAvailabilityEntityToTransfer(SpyProductAbstract $productAbstractEntity)
    {
        $productAbstractAvailabilityTransfer = (new ProductAbstractAvailabilityTransfer())
            ->setSku($productAbstractEntity->getSku())
            ->setProductName($productAbstractEntity->getVirtualColumn('productName'))
            ->setAvailability($this->availabilityQueryContainer->getAvailabilityQuantity($productAbstractEntity))
            ->setReservationQuantity(
                $this->calculateReservation($this->availabilityQueryContainer->getReservationQuantity($productAbstractEntity))
            );

        $this->setAbstractNeverOutOfStock($productAbstractEntity, $productAbstractAvailabilityTransfer);

        return $productAbstractAvailabilityTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return void
     */
    protected function setAbstractNeverOutOfStock(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
    ) {
        $neverOutOfStockSet = explode(',', $this->availabilityQueryContainer->getConcreteNeverOutOfStockSet($productAbstractEntity));

        $productAbstractAvailabilityTransfer->setIsNeverOutOfStock(false);
        foreach ($neverOutOfStockSet as $status) {
            if (filter_var($status, FILTER_VALIDATE_BOOLEAN)) {
                $productAbstractAvailabilityTransfer->setIsNeverOutOfStock(true);
                break;
            }
        }
    }
}
