<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Availability\AvailabilityConfig;
use Spryker\Zed\Availability\Business\Exception\ProductNotFoundException;
use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

class AvailabilityHandler implements AvailabilityHandlerInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_SKU_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT = 'The product was not found with this SKU: %s';

    /**
     * @var string
     */
    protected const PRODUCT_ID_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT = 'The product was not found with this ID: %d';

    /**
     * @var string
     */
    protected const TABLE_NAME = 'spy_stock_product';

    /**
     * @var string
     */
    protected const FK_PRODUCT = 'fk_product';

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface
     */
    protected $availabilityRepository;

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface
     */
    protected $availabilityEntityManager;

    /**
     * @var \Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface
     */
    protected $availabilityCalculator;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface
     */
    protected AvailabilityToProductFacadeInterface $productFacade;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface $availabilityEntityManager
     * @param \Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface $availabilityCalculator
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface $productFacade
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityEntityManagerInterface $availabilityEntityManager,
        ProductAvailabilityCalculatorInterface $availabilityCalculator,
        AvailabilityToTouchFacadeInterface $touchFacade,
        AvailabilityToStockFacadeInterface $stockFacade,
        AvailabilityToEventFacadeInterface $eventFacade,
        AvailabilityToProductFacadeInterface $productFacade
    ) {
        $this->availabilityCalculator = $availabilityCalculator;
        $this->availabilityRepository = $availabilityRepository;
        $this->availabilityEntityManager = $availabilityEntityManager;
        $this->touchFacade = $touchFacade;
        $this->stockFacade = $stockFacade;
        $this->eventFacade = $eventFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $concreteSku
     *
     * @return void
     */
    public function updateAvailability($concreteSku)
    {
        $storeTransfersRelatedToProductAvailability = $this->availabilityRepository->getStoresWhereProductAvailabilityIsDefined($concreteSku);
        $storeTransfersRelatedToProductStock = $this->stockFacade->getStoresWhereProductStockIsDefined($concreteSku);

        $storeTransfersRelatedToProductAvailabilityWithoutProductStockRelation = $this->getStoreTransfersRelatedToProductAvailabilityWithoutProductStockRelation(
            $storeTransfersRelatedToProductAvailability,
            $storeTransfersRelatedToProductStock,
        );
        if ($storeTransfersRelatedToProductAvailabilityWithoutProductStockRelation !== []) {
            $this->updateProductAvailabilityForProductWithNotDefinedStock(
                $concreteSku,
                $storeTransfersRelatedToProductAvailabilityWithoutProductStockRelation,
            );
        }

        foreach ($storeTransfersRelatedToProductStock as $storeTransfer) {
            $this->updateAvailabilityForStore($concreteSku, $storeTransfer);
        }
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function updateAvailabilityForStore(string $concreteSku, StoreTransfer $storeTransfer): void
    {
        $quantity = $this->availabilityCalculator->calculateAvailabilityForProductConcrete($concreteSku, $storeTransfer);

        $this->saveAndTouchAvailability($concreteSku, $quantity, $storeTransfer);
    }

    /**
     * @param string $concreteSku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveAndTouchAvailability(string $concreteSku, Decimal $quantity, StoreTransfer $storeTransfer): int
    {
        $wasProductConcreteAvailable = $this->isProductConcreteAvailable(
            $this->availabilityRepository->findProductConcreteAvailabilityBySkuAndStore($concreteSku, $storeTransfer),
        );

        /** @var string $abstractSku */
        $abstractSku = $this->availabilityRepository->getAbstractSkuFromProductConcrete($concreteSku);
        $productConcreteAvailabilityTransfer = (new ProductConcreteAvailabilityTransfer())
            ->setSku($concreteSku)
            ->setIsNeverOutOfStock($this->availabilityCalculator->isNeverOutOfStockForStore($concreteSku, $storeTransfer))
            ->setAvailability($quantity);

        $this->updateProductAbstractAvailabilityBySku($abstractSku, $storeTransfer);
        $idAvailabilityAbstract = $this->availabilityRepository
            ->findIdProductAbstractAvailabilityBySku($abstractSku, $storeTransfer);

        $isAvailabilityChanged = $this->availabilityEntityManager->saveProductConcreteAvailability(
            $productConcreteAvailabilityTransfer,
            $storeTransfer,
            $abstractSku,
        );

        if ($isAvailabilityChanged) {
            $this->touchAvailabilityAbstract($idAvailabilityAbstract);
        }

        if ($isAvailabilityChanged && !$wasProductConcreteAvailable && $this->isProductConcreteAvailable($productConcreteAvailabilityTransfer)) {
            $this->triggerProductIsAvailableAgainEvent($concreteSku, $storeTransfer);
        }

        return $idAvailabilityAbstract;
    }

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return void
     */
    public function touchAvailabilityAbstract($idAvailabilityAbstract)
    {
        $this->touchFacade->touchActive(AvailabilityConfig::RESOURCE_TYPE_AVAILABILITY_ABSTRACT, $idAvailabilityAbstract);
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function updateProductConcreteAvailabilityById(
        int $idProductConcrete,
        StoreTransfer $storeTransfer
    ): ProductConcreteAvailabilityTransfer {
        $concreteSku = $this->getProductConcreteSkuByConcreteId($idProductConcrete);

        return $this->updateProductConcreteAvailabilityBySku($concreteSku, $storeTransfer);
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function updateProductConcreteAvailabilityBySku(
        string $concreteSku,
        StoreTransfer $storeTransfer
    ): ProductConcreteAvailabilityTransfer {
        $abstractSku = $this->getAbstractSkuFromProductConcrete($concreteSku);

        $wasProductConcreteAvailable = $this->isProductConcreteAvailable(
            $this->availabilityRepository->findProductConcreteAvailabilityBySkuAndStore($concreteSku, $storeTransfer),
        );

        $productConcreteAvailabilityTransfer = $this->availabilityCalculator
            ->getCalculatedProductConcreteAvailabilityTransfer($concreteSku, $storeTransfer);

        $this->updateProductAbstractAvailabilityBySku($abstractSku, $storeTransfer);

        $isAvailabilityChanged = $this->availabilityEntityManager->saveProductConcreteAvailability(
            $productConcreteAvailabilityTransfer,
            $storeTransfer,
            $abstractSku,
        );

        /** @var string $sku */
        $sku = $productConcreteAvailabilityTransfer->requireSku()->getSku();
        if ($isAvailabilityChanged && !$wasProductConcreteAvailable && $this->isProductConcreteAvailable($productConcreteAvailabilityTransfer)) {
            $this->triggerProductIsAvailableAgainEvent($sku, $storeTransfer);
        }

        return $productConcreteAvailabilityTransfer;
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function updateProductAbstractAvailabilityBySku(
        string $abstractSku,
        StoreTransfer $storeTransfer
    ): ProductAbstractAvailabilityTransfer {
        $productAbstractAvailabilityTransfer = $this->availabilityCalculator
            ->getCalculatedProductAbstractAvailabilityTransfer($abstractSku, $storeTransfer);

        $this->availabilityEntityManager->saveProductAbstractAvailability(
            $productAbstractAvailabilityTransfer,
            $storeTransfer,
        );

        return $productAbstractAvailabilityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function updateAvailabilityByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        if ($dynamicEntityPostEditRequestTransfer->getTableNameOrFail() !== static::TABLE_NAME) {
            return $this->createDynamicEntityPostEditResponseTransfer();
        }

        foreach ($dynamicEntityPostEditRequestTransfer->getRawDynamicEntities() as $rawDynamicEntity) {
            $productConcreteTransfer = $this->productFacade->findProductConcreteById($rawDynamicEntity->getFields()[static::FK_PRODUCT]);
            if ($productConcreteTransfer !== null) {
                $this->updateAvailability($productConcreteTransfer->getSkuOrFail());
            }
        }

        return $this->createDynamicEntityPostEditResponseTransfer();
    }

    /**
     * @param string $concreteSku
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return void
     */
    protected function updateProductAvailabilityForProductWithNotDefinedStock(string $concreteSku, array $storeTransfers): void
    {
        foreach ($storeTransfers as $storeTransfer) {
            $this->saveAndTouchAvailability($concreteSku, new Decimal(0), $storeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $productConcreteAvailabilityTransfer
     *
     * @return bool
     */
    protected function isProductConcreteAvailable(?ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer): bool
    {
        if ($productConcreteAvailabilityTransfer === null) {
            return false;
        }

        /** @var \Spryker\DecimalObject\Decimal $availability */
        $availability = $productConcreteAvailabilityTransfer->requireAvailability()->getAvailability();

        return $availability->greaterThan(0) ||
            $productConcreteAvailabilityTransfer->getIsNeverOutOfStock() === true;
    }

    /**
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Availability\Business\Exception\ProductNotFoundException
     *
     * @return string
     */
    protected function getAbstractSkuFromProductConcrete(string $concreteSku): string
    {
        $abstractSku = $this->availabilityRepository->getAbstractSkuFromProductConcrete($concreteSku);

        if ($abstractSku === null) {
            throw new ProductNotFoundException(
                sprintf(static::PRODUCT_SKU_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT, $concreteSku),
            );
        }

        return $abstractSku;
    }

    /**
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Availability\Business\Exception\ProductNotFoundException
     *
     * @return string
     */
    protected function getProductConcreteSkuByConcreteId(int $idProductConcrete): string
    {
        $concreteSku = $this->availabilityRepository->getProductConcreteSkuByConcreteId($idProductConcrete);

        if ($concreteSku === null) {
            throw new ProductNotFoundException(
                sprintf(static::PRODUCT_ID_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT, $idProductConcrete),
            );
        }

        return $concreteSku;
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function triggerProductIsAvailableAgainEvent(string $concreteSku, StoreTransfer $storeTransfer): void
    {
        $availabilityNotificationDataTransfer = (new AvailabilityNotificationDataTransfer())
            ->setSku($concreteSku)
            ->setStore($storeTransfer);

        $this->eventFacade->trigger(
            AvailabilityEvents::AVAILABILITY_NOTIFICATION,
            $availabilityNotificationDataTransfer,
        );
    }

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    protected function createDynamicEntityPostEditResponseTransfer(): DynamicEntityPostEditResponseTransfer
    {
        return new DynamicEntityPostEditResponseTransfer();
    }

    /**
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfersRelatedToProductAvailability
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfersRelatedToProductStock
     *
     * @return list<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getStoreTransfersRelatedToProductAvailabilityWithoutProductStockRelation(
        array $storeTransfersRelatedToProductAvailability,
        array $storeTransfersRelatedToProductStock
    ): array {
        $storeTransfersRelatedToProductStockIndexedByIdStore = $this->getStoreTransfersIndexedByIdStore($storeTransfersRelatedToProductStock);
        $storeTransfersRelatedToProductAvailabilityWithoutProductStockRelation = [];
        foreach ($storeTransfersRelatedToProductAvailability as $storeTransfer) {
            if (isset($storeTransfersRelatedToProductStockIndexedByIdStore[$storeTransfer->getIdStoreOrFail()])) {
                continue;
            }

            $storeTransfersRelatedToProductAvailabilityWithoutProductStockRelation[] = $storeTransfer;
        }

        return $storeTransfersRelatedToProductAvailabilityWithoutProductStockRelation;
    }

    /**
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getStoreTransfersIndexedByIdStore(array $storeTransfers): array
    {
        $storeTransfersIndexedByIdStore = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeTransfersIndexedByIdStore[$storeTransfer->getIdStoreOrFail()] = $storeTransfer;
        }

        return $storeTransfersIndexedByIdStore;
    }
}
