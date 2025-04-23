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
use Generated\Shared\Transfer\ProductAvailabilityDataTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Availability\AvailabilityConfig;
use Spryker\Zed\Availability\Business\Exception\ProductNotFoundException;
use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
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
     * @var list<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected static $allStoreTransfersCache = [];

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
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     */
    protected AvailabilityToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface $availabilityEntityManager
     * @param \Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface $availabilityCalculator
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityEntityManagerInterface $availabilityEntityManager,
        ProductAvailabilityCalculatorInterface $availabilityCalculator,
        AvailabilityToTouchFacadeInterface $touchFacade,
        AvailabilityToStockFacadeInterface $stockFacade,
        AvailabilityToEventFacadeInterface $eventFacade,
        AvailabilityToProductFacadeInterface $productFacade,
        AvailabilityToStoreFacadeInterface $storeFacade
    ) {
        $this->availabilityCalculator = $availabilityCalculator;
        $this->availabilityRepository = $availabilityRepository;
        $this->availabilityEntityManager = $availabilityEntityManager;
        $this->touchFacade = $touchFacade;
        $this->stockFacade = $stockFacade;
        $this->eventFacade = $eventFacade;
        $this->productFacade = $productFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $concreteSku
     *
     * @return void
     */
    public function updateAvailability(string $concreteSku): void
    {
        $productAvailabilityDataTransfer = $this->availabilityRepository->getProductConcreteWithAvailability($concreteSku);

        $this->saveAllAvailabilities($productAvailabilityDataTransfer);
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

        /** @var string $productAbstractSku */
        $productAbstractSku = $this->availabilityRepository->getAbstractSkuFromProductConcrete($concreteSku);
        $productConcreteAvailabilityTransfer = (new ProductConcreteAvailabilityTransfer())
            ->setSku($concreteSku)
            ->setIsNeverOutOfStock($this->availabilityCalculator->isNeverOutOfStockForStore($concreteSku, $storeTransfer))
            ->setAvailability($quantity);

        $this->updateProductAbstractAvailabilityBySku($productAbstractSku, $storeTransfer);
        $idAvailabilityAbstract = $this->availabilityRepository
            ->findIdProductAbstractAvailabilityBySku($productAbstractSku, $storeTransfer);

        $isAvailabilityChanged = $this->availabilityEntityManager->saveProductConcreteAvailability(
            $productConcreteAvailabilityTransfer,
            $storeTransfer,
            $productAbstractSku,
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
        $productAbstractSku = $this->getAbstractSkuFromProductConcrete($concreteSku);

        $wasProductConcreteAvailable = $this->isProductConcreteAvailable(
            $this->availabilityRepository->findProductConcreteAvailabilityBySkuAndStore($concreteSku, $storeTransfer),
        );

        $productConcreteAvailabilityTransfer = $this->availabilityCalculator
            ->getCalculatedProductConcreteAvailabilityTransfer($concreteSku, $storeTransfer);

        $this->updateProductAbstractAvailabilityBySku($productAbstractSku, $storeTransfer);

        $isAvailabilityChanged = $this->availabilityEntityManager->saveProductConcreteAvailability(
            $productConcreteAvailabilityTransfer,
            $storeTransfer,
            $productAbstractSku,
        );

        /** @var string $sku */
        $sku = $productConcreteAvailabilityTransfer->requireSku()->getSku();
        if ($isAvailabilityChanged && !$wasProductConcreteAvailable && $this->isProductConcreteAvailable($productConcreteAvailabilityTransfer)) {
            $this->triggerProductIsAvailableAgainEvent($sku, $storeTransfer);
        }

        return $productConcreteAvailabilityTransfer;
    }

    /**
     * @param string $productAbstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function updateProductAbstractAvailabilityBySku(
        string $productAbstractSku,
        StoreTransfer $storeTransfer
    ): ProductAbstractAvailabilityTransfer {
        $productAbstractAvailabilityTransfer = $this->availabilityCalculator
            ->getCalculatedProductAbstractAvailabilityTransfer($productAbstractSku, $storeTransfer);

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

        $productIds = $this->getProductIdsFromDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
        $productConcreteSkus = $this->productFacade->getProductConcreteSkusByConcreteIds($productIds);
        foreach (array_keys($productConcreteSkus) as $sku) {
            $this->updateAvailability($sku);
        }

        return $this->createDynamicEntityPostEditResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return array<int>
     */
    protected function getProductIdsFromDynamicEntityRequest(DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer): array
    {
        $productIds = [];
        foreach ($dynamicEntityPostEditRequestTransfer->getRawDynamicEntities() as $rawDynamicEntity) {
            $productIds[] = $rawDynamicEntity->getFields()[static::FK_PRODUCT];
        }

        return $productIds;
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
        $productAbstractSku = $this->availabilityRepository->getAbstractSkuFromProductConcrete($concreteSku);

        if ($productAbstractSku === null) {
            throw new ProductNotFoundException(
                sprintf(static::PRODUCT_SKU_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT, $concreteSku),
            );
        }

        return $productAbstractSku;
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
     * @param \Generated\Shared\Transfer\ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
     *
     * @return array<int, array<\Generated\Shared\Transfer\StockProductTransfer>>
     */
    protected function getStockProductTransfersIndexedByIdStore(ProductAvailabilityDataTransfer $productAvailabilityDataTransfer): array
    {
        $stockProductsIndexedByStock = [];
        foreach ($productAvailabilityDataTransfer->getStockProducts() as $stockProductTransfer) {
            $stockProductsIndexedByStock[$stockProductTransfer->getFkStock()] = $stockProductTransfer;
        }

        $stockProductTransfersIndexedByIdStore = [];
        foreach ($productAvailabilityDataTransfer->getStocks() as $stockTransfer) {
            foreach ($stockTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
                if (!isset($stockProductsIndexedByStock[$stockTransfer->getIdStock()])) {
                    continue;
                }
                $stockProductTransfersIndexedByIdStore[(int)$storeTransfer->getIdStore()][] = $stockProductsIndexedByStock[$stockTransfer->getIdStock()];
            }
        }

        return $stockProductTransfersIndexedByIdStore;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer>
     */
    protected function getProductConcreteAvailabilityTransfersIndexedByIdStore(ProductAvailabilityDataTransfer $productAvailabilityDataTransfer): array
    {
        $productConcreteAvailabilityTransfersIndexedByIdStore = [];
        foreach ($productAvailabilityDataTransfer->getProductConcreteAvailabilities() as $productConcreteAvailabilityTransfer) {
            $productConcreteAvailabilityTransfersIndexedByIdStore[(int)$productConcreteAvailabilityTransfer->getStoreOrFail()->getIdStore()] = $productConcreteAvailabilityTransfer;
        }

        return $productConcreteAvailabilityTransfersIndexedByIdStore;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer>
     */
    protected function getProductAbstractAvailabilityTransfersIndexedByIdStore(ProductAvailabilityDataTransfer $productAvailabilityDataTransfer): array
    {
        $productAbstractAvailabilityTransfersIndexedByIdStore = [];
        foreach ($productAvailabilityDataTransfer->getProductAbstractAvailabilities() as $productAbstractAvailabilityTransfer) {
            $productAbstractAvailabilityTransfersIndexedByIdStore[(int)$productAbstractAvailabilityTransfer->getIdStore()] = $productAbstractAvailabilityTransfer;
        }

        return $productAbstractAvailabilityTransfersIndexedByIdStore;
    }

    /**
     * @param string $productAbstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<int, \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer> $productAbstractAvailabilityTransfersIndexedByIdStore
     *
     * @return void
     */
    protected function updateProductAbstractAvailability(
        string $productAbstractSku,
        StoreTransfer $storeTransfer,
        array $productAbstractAvailabilityTransfersIndexedByIdStore
    ): void {
        $calculatedProductAbstractAvailabilityTransfer = $this->availabilityCalculator
            ->getCalculatedProductAbstractAvailabilityTransfer($productAbstractSku, $storeTransfer);

        /** @var \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null $productAbstractAvailabilityTransfer */
        $productAbstractAvailabilityTransfer = $productAbstractAvailabilityTransfersIndexedByIdStore[$storeTransfer->getIdStore()] ?? null;
        $abstractAvailabilityQuantity = isset($productAbstractAvailabilityTransfersIndexedByIdStore[$storeTransfer->getIdStore()]) ?
            $productAbstractAvailabilityTransfer?->getAvailability() :
            null;

        if ($abstractAvailabilityQuantity === null || !$calculatedProductAbstractAvailabilityTransfer->getAvailabilityOrFail()->equals($abstractAvailabilityQuantity)) {
            $this->availabilityEntityManager->saveProductAbstractAvailability(
                $calculatedProductAbstractAvailabilityTransfer,
                $storeTransfer,
            );
        }
    }

    /**
     * @return list<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getAllStoreTransfersCache(): array
    {
        if (static::$allStoreTransfersCache) {
            return static::$allStoreTransfersCache;
        }
        static::$allStoreTransfersCache = $this->storeFacade->getAllStores();

        return static::$allStoreTransfersCache;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
     *
     * @return void
     */
    protected function saveAllAvailabilities(ProductAvailabilityDataTransfer $productAvailabilityDataTransfer): void
    {
        if (!$productAvailabilityDataTransfer->getProductConcrete() && !$productAvailabilityDataTransfer->getProductAbstract()) {
            return;
        }

        $stockProductTransfersIndexedByIdStore = $this->getStockProductTransfersIndexedByIdStore($productAvailabilityDataTransfer);
        $productConcreteAvailabilityTransfersIndexedByIdStore = $this->getProductConcreteAvailabilityTransfersIndexedByIdStore($productAvailabilityDataTransfer);
        $productAbstractAvailabilityTransfersIndexedByIdStore = $this->getProductAbstractAvailabilityTransfersIndexedByIdStore($productAvailabilityDataTransfer);
        $productAbstractSku = $productAvailabilityDataTransfer->getProductAbstractOrFail()->getSkuOrFail();
        $productConcreteSku = $productAvailabilityDataTransfer->getProductConcreteOrFail()->getSkuOrFail();

        foreach ($this->getAllStoreTransfersCache() as $storeTransfer) {
            $this->executeSaveAllAvailabilitiesPerStore(
                $stockProductTransfersIndexedByIdStore,
                $productConcreteAvailabilityTransfersIndexedByIdStore,
                $productAbstractAvailabilityTransfersIndexedByIdStore,
                $productAbstractSku,
                $productConcreteSku,
                $storeTransfer,
            );
        }
    }

    /**
     * @param array<int, array<\Generated\Shared\Transfer\StockProductTransfer>> $stockProductTransfersIndexedByIdStore
     * @param array<int, \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer> $productConcreteAvailabilityTransfersIndexedByIdStore
     * @param array<int, \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer> $productAbstractAvailabilityTransfersIndexedByIdStore
     * @param string $productAbstractSku
     * @param string $productConcreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function executeSaveAllAvailabilitiesPerStore(
        array $stockProductTransfersIndexedByIdStore,
        array $productConcreteAvailabilityTransfersIndexedByIdStore,
        array $productAbstractAvailabilityTransfersIndexedByIdStore,
        string $productAbstractSku,
        string $productConcreteSku,
        StoreTransfer $storeTransfer
    ): void {
        $wasProductConcreteAvailable = $this->isProductConcreteAvailable(
            $productConcreteAvailabilityTransfersIndexedByIdStore[$storeTransfer->getIdStore()] ?? null,
        );

        $this->updateProductAbstractAvailability($productAbstractSku, $storeTransfer, $productAbstractAvailabilityTransfersIndexedByIdStore);

        $quantity = new Decimal(0);
        if (isset($stockProductTransfersIndexedByIdStore[$storeTransfer->getIdStore()])) {
            $quantity = $this->availabilityCalculator->calculateAvailabilityForProductConcrete(
                $productConcreteSku,
                $storeTransfer,
                $stockProductTransfersIndexedByIdStore[$storeTransfer->getIdStore()],
            );
        }

        $isNeverOutOfStock = $this->isNeverOutOfStock($stockProductTransfersIndexedByIdStore, $storeTransfer);
        $productConcreteAvailabilityTransfer = (new ProductConcreteAvailabilityTransfer())
            ->setSku($productConcreteSku)
            ->setIsNeverOutOfStock($isNeverOutOfStock)
            ->setAvailability($quantity);

        if (isset($productConcreteAvailabilityTransfersIndexedByIdStore[$storeTransfer->getIdStore()])) {
            $productConcreteAvailabilityTransfer = $productConcreteAvailabilityTransfersIndexedByIdStore[$storeTransfer->getIdStore()];
        }

        $isAvailabilityChanged = false;
        if (
            !$quantity->equals($productConcreteAvailabilityTransfer->getAvailability() ?? 0) ||
            $productConcreteAvailabilityTransfer->getIsNeverOutOfStock() !== $isNeverOutOfStock ||
            !isset($productConcreteAvailabilityTransfersIndexedByIdStore[$storeTransfer->getIdStore()])
        ) {
            $productConcreteAvailabilityTransfer->setAvailability($quantity);
            $productConcreteAvailabilityTransfer->setIsNeverOutOfStock($isNeverOutOfStock);
            $isAvailabilityChanged = $this->availabilityEntityManager->saveProductConcreteAvailability(
                $productConcreteAvailabilityTransfer,
                $storeTransfer,
                $productAbstractSku,
            );
        }

        if ($isAvailabilityChanged && $this->touchFacade->isTouchEnabled()) {
            $idAvailabilityAbstract = $productConcreteAvailabilityTransfer->getFkAvailabilityAbstract();
            if ($idAvailabilityAbstract === null) {
                $idAvailabilityAbstract = $this->availabilityRepository
                    ->findIdProductAbstractAvailabilityBySku($productAbstractSku, $storeTransfer);
            }
            $this->touchAvailabilityAbstract($idAvailabilityAbstract);
        }

        if ($isAvailabilityChanged && !$wasProductConcreteAvailable && $this->isProductConcreteAvailable($productConcreteAvailabilityTransfer)) {
            $this->triggerProductIsAvailableAgainEvent($productConcreteSku, $storeTransfer);
        }
    }

    /**
     * @param array<int, array<\Generated\Shared\Transfer\StockProductTransfer>> $stockProductTransfersIndexedByIdStore
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isNeverOutOfStock(array $stockProductTransfersIndexedByIdStore, StoreTransfer $storeTransfer): bool
    {
        if (!isset($stockProductTransfersIndexedByIdStore[$storeTransfer->getIdStore()])) {
            return false;
        }

        foreach ($stockProductTransfersIndexedByIdStore[$storeTransfer->getIdStore()] as $stockProductTransfer) {
            if ($stockProductTransfer->getIsNeverOutOfStock()) {
                return true;
            }
        }

        return false;
    }
}
