<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Availability\AvailabilityConfig;
use Spryker\Zed\Availability\Business\Exception\ProductNotFoundException;
use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

class AvailabilityHandler implements AvailabilityHandlerInterface
{
    protected const PRODUCT_SKU_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT = 'The product was not found with this SKU: %s';
    protected const PRODUCT_ID_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT = 'The product was not found with this ID: %d';

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
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface $availabilityEntityManager
     * @param \Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface $availabilityCalculator
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface $touchFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface $eventFacade
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityEntityManagerInterface $availabilityEntityManager,
        ProductAvailabilityCalculatorInterface $availabilityCalculator,
        AvailabilityToTouchInterface $touchFacade,
        AvailabilityToStoreFacadeInterface $storeFacade,
        AvailabilityToEventFacadeInterface $eventFacade
    ) {
        $this->availabilityCalculator = $availabilityCalculator;
        $this->availabilityRepository = $availabilityRepository;
        $this->availabilityEntityManager = $availabilityEntityManager;
        $this->touchFacade = $touchFacade;
        $this->storeFacade = $storeFacade;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param string $concreteSku
     *
     * @return void
     */
    public function updateAvailability($concreteSku)
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $this->updateAvailabilityForStore($concreteSku, $storeTransfer);

        $sharedStores = $storeTransfer->getStoresWithSharedPersistence();
        foreach ($sharedStores as $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
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
            $this->availabilityRepository->findProductConcreteAvailabilityBySkuAndStore($concreteSku, $storeTransfer)
        );

        $abstractSku = $this->availabilityRepository->getAbstractSkuFromProductConcrete($concreteSku);
        $productConcreteAvailabilityTransfer = (new ProductConcreteAvailabilityTransfer())
            ->setSku($concreteSku)
            ->setAvailability($quantity);

        $this->updateProductAbstractAvailabilityBySku($abstractSku, $storeTransfer);
        $idAvailabilityAbstract = $this->availabilityRepository
            ->findIdProductAbstractAvailabilityBySku($abstractSku, $storeTransfer);

        $isAvailabilityChanged = $this->availabilityEntityManager->saveProductConcreteAvailability(
            $productConcreteAvailabilityTransfer,
            $storeTransfer,
            $abstractSku
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
        $wasProductConcreteAvailable = $this->isProductConcreteAvailable(
            $this->availabilityRepository->findProductConcreteAvailabilityBySkuAndStore($concreteSku, $storeTransfer)
        );

        $productConcreteAvailabilityTransfer = $this->availabilityCalculator
            ->getCalculatedProductConcreteAvailabilityTransfer($concreteSku, $storeTransfer);

        $abstractSku = $this->getAbstractSkuFromProductConcrete($concreteSku);

        $this->updateProductAbstractAvailabilityBySku($abstractSku, $storeTransfer);

        $isAvailabilityChanged = $this->availabilityEntityManager->saveProductConcreteAvailability(
            $productConcreteAvailabilityTransfer,
            $storeTransfer,
            $abstractSku
        );

        if ($isAvailabilityChanged && !$wasProductConcreteAvailable && $this->isProductConcreteAvailable($productConcreteAvailabilityTransfer)) {
            $this->triggerProductIsAvailableAgainEvent($productConcreteAvailabilityTransfer->getSku(), $storeTransfer);
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
            $storeTransfer
        );

        return $productAbstractAvailabilityTransfer;
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

        return $productConcreteAvailabilityTransfer->getAvailability()->greaterThan(0) ||
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
                sprintf(static::PRODUCT_SKU_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT, $concreteSku)
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
                sprintf(static::PRODUCT_ID_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT, $idProductConcrete)
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
            $availabilityNotificationDataTransfer
        );
    }
}
