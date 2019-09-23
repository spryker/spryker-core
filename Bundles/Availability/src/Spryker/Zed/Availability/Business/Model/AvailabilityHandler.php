<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Availability\AvailabilityConfig;
use Spryker\Zed\Availability\Business\Exception\ProductNotFoundException;
use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;
use Spryker\Zed\Product\Business\Exception\MissingProductException;

class AvailabilityHandler implements AvailabilityHandlerInterface
{
    protected const PRODUCT_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT = 'The product was not found with this SKU: %s';

    /**
     * @var \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    protected $sellable;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface $touchFacade
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface $productFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface $eventFacade
     */
    public function __construct(
        SellableInterface $sellable,
        AvailabilityToStockInterface $stockFacade,
        AvailabilityToTouchInterface $touchFacade,
        AvailabilityQueryContainerInterface $queryContainer,
        AvailabilityToProductInterface $productFacade,
        AvailabilityToStoreFacadeInterface $storeFacade,
        AvailabilityToEventFacadeInterface $eventFacade
    ) {
        $this->sellable = $sellable;
        $this->stockFacade = $stockFacade;
        $this->touchFacade = $touchFacade;
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->storeFacade = $storeFacade;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku)
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $this->updateAvailabilityForStore($sku, $storeTransfer);

        $sharedStores = $storeTransfer->getStoresWithSharedPersistence();
        foreach ($sharedStores as $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
            $this->updateAvailabilityForStore($sku, $storeTransfer);
        }
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function updateAvailabilityForStore(string $sku, StoreTransfer $storeTransfer): void
    {
        $quantity = $this->sellable->calculateAvailabilityForProductWithStore($sku, $storeTransfer);
        $quantityWithReservedItems = $this->getQuantity($quantity);

        $this->saveAndTouchAvailability($sku, $quantityWithReservedItems, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return int
     */
    public function saveCurrentAvailability(string $sku, Decimal $quantity): int
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $spyAvailabilityEntity = $this->saveAndTouchAvailability($sku, $quantity, $storeTransfer);

        return $spyAvailabilityEntity->getFkAvailabilityAbstract();
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveCurrentAvailabilityForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): int
    {
        $spyAvailabilityEntity = $this->saveAndTouchAvailability($sku, $quantity, $storeTransfer);

        return $spyAvailabilityEntity->getFkAvailabilityAbstract();
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function saveAndTouchAvailability(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): SpyAvailability
    {
        $currentQuantity = $this->findCurrentPhysicalQuantity($sku, $storeTransfer) ?? new Decimal(0);
        $spyAvailabilityEntity = $this->prepareAvailabilityEntityForSave($sku, $quantity, $storeTransfer);
        $isNeverOutOfStockModified = $spyAvailabilityEntity->isColumnModified(SpyAvailabilityTableMap::COL_IS_NEVER_OUT_OF_STOCK);
        $isAvailabilityChanged = $isNeverOutOfStockModified || $this->isAvailabilityStatusChanged($currentQuantity, $quantity);

        $spyAvailabilityEntity->save();

        $this->updateAbstractAvailabilityQuantity($spyAvailabilityEntity->getFkAvailabilityAbstract(), $storeTransfer);

        if ($isAvailabilityChanged) {
            $this->touchAvailabilityAbstract($spyAvailabilityEntity->getFkAvailabilityAbstract());
        }

        if ($isAvailabilityChanged && ($quantity->greaterThan(0) || $spyAvailabilityEntity->getIsNeverOutOfStock() === true)) {
            $this->triggerProductIsAvailableAgainEvent($sku, $storeTransfer);
        }

        return $spyAvailabilityEntity;
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function prepareAvailabilityEntityForSave(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): SpyAvailability
    {
        $spyAvailabilityEntity = $this->querySpyAvailabilityBySku($sku, $storeTransfer)->findOneOrCreate();

        if ($spyAvailabilityEntity->isNew()) {
            $availabilityAbstractEntity = $this->findOrCreateSpyAvailabilityAbstract($sku, $storeTransfer);
            $spyAvailabilityEntity->setFkAvailabilityAbstract($availabilityAbstractEntity->getIdAvailabilityAbstract());
        }

        $spyAvailabilityEntity->setQuantity($quantity);
        $spyAvailabilityEntity->setIsNeverOutOfStock(
            $this->stockFacade->isNeverOutOfStockForStore($sku, $storeTransfer)
        );

        return $spyAvailabilityEntity;
    }

    /**
     * @param \Spryker\DecimalObject\Decimal|null $currentQuantity
     * @param \Spryker\DecimalObject\Decimal|null $quantityWithReservedItems
     *
     * @return bool
     */
    protected function isAvailabilityStatusChanged(?Decimal $currentQuantity, ?Decimal $quantityWithReservedItems): bool
    {
        if ($currentQuantity === null && $quantityWithReservedItems !== null) {
            return true;
        }

        if ($currentQuantity === null || $quantityWithReservedItems === null) {
            return false;
        }

        if ($currentQuantity->equals(0) && $quantityWithReservedItems->greaterThan($currentQuantity)) {
            return true;
        }

        if (!$currentQuantity->equals(0) && $quantityWithReservedItems->equals(0)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    protected function querySpyAvailabilityBySku($sku, StoreTransfer $storeTransfer)
    {
        return $this->queryContainer->queryAvailabilityBySkuAndIdStore($sku, $storeTransfer->getIdStore());
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
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getQuantity(Decimal $quantity): Decimal
    {
        return $quantity->greaterThan(0) ? $quantity : new Decimal(0);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal|null
     */
    protected function findCurrentPhysicalQuantity(string $sku, StoreTransfer $storeTransfer): ?Decimal
    {
        $availabilityEntity = $this->querySpyAvailabilityBySku($sku, $storeTransfer)->findOne();

        if ($availabilityEntity === null) {
            return null;
        }

        return new Decimal($availabilityEntity->getQuantity());
    }

    /**
     * @param int $idAvailabilityAbstract
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function updateAbstractAvailabilityQuantity($idAvailabilityAbstract, StoreTransfer $storeTransfer)
    {
        $availabilityAbstractEntity = $this->queryContainer
            ->queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract, $storeTransfer->getIdStore())
            ->findOne();

        /** @var int|null $sumQuantity */
        $sumQuantity = $this->queryContainer
            ->querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract, $storeTransfer->getIdStore())
            ->findOne();

        $availabilityAbstractEntity->setFkStore($storeTransfer->getIdStore());
        $availabilityAbstractEntity->setQuantity($sumQuantity);
        $availabilityAbstractEntity->save();
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    protected function findOrCreateSpyAvailabilityAbstract(string $sku, StoreTransfer $storeTransfer): SpyAvailabilityAbstract
    {
        $abstractSku = $this->getAbstractSkuFromProductConcrete($sku);
        $availabilityAbstractEntity = $this->queryContainer
            ->querySpyAvailabilityAbstractByAbstractSku($abstractSku)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->findOne();

        if ($availabilityAbstractEntity !== null) {
            return $availabilityAbstractEntity;
        }

        return $this->createSpyAvailabilityAbstract($abstractSku, $storeTransfer);
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Availability\Business\Exception\ProductNotFoundException
     *
     * @return string
     */
    protected function getAbstractSkuFromProductConcrete(string $sku): string
    {
        try {
            return $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        } catch (MissingProductException $exception) {
            throw new ProductNotFoundException(
                sprintf(static::PRODUCT_NOT_FOUND_EXCEPTION_MESSAGE_FORMAT, $sku)
            );
        }
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    protected function createSpyAvailabilityAbstract(string $abstractSku, StoreTransfer $storeTransfer): SpyAvailabilityAbstract
    {
        $availableAbstractEntity = (new SpyAvailabilityAbstract())
            ->setAbstractSku($abstractSku)
            ->setFkStore($storeTransfer->getIdStore());

        $availableAbstractEntity->save();

        return $availableAbstractEntity;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function triggerProductIsAvailableAgainEvent(string $sku, StoreTransfer $storeTransfer): void
    {
        $availabilityNotificationDataTransfer = (new AvailabilityNotificationDataTransfer())
            ->setSku($sku)
            ->setStore($storeTransfer);
        $this->eventFacade->trigger(
            AvailabilityEvents::AVAILABILITY_NOTIFICATION,
            $availabilityNotificationDataTransfer
        );
    }
}
