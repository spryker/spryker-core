<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Spryker\Shared\Availability\AvailabilityConfig;
use Spryker\Zed\Availability\Business\Exception\ProductNotFoundException;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;

class AvailabilityHandler implements AvailabilityHandlerInterface
{
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
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface $touchFacade
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface $productFacade
     */
    public function __construct(
        SellableInterface $sellable,
        AvailabilityToStockInterface $stockFacade,
        AvailabilityToTouchInterface $touchFacade,
        AvailabilityQueryContainerInterface $queryContainer,
        AvailabilityToProductInterface $productFacade
    ) {
        $this->sellable = $sellable;
        $this->stockFacade = $stockFacade;
        $this->touchFacade = $touchFacade;
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku)
    {
        $quantityWithReservedItems = $this->getQuantity($this->sellable->calculateStockForProduct($sku));

        $this->saveAndTouchAvailability($sku, $quantityWithReservedItems);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return int
     */
    public function saveCurrentAvailability($sku, $quantity)
    {
        $spyAvailabilityEntity = $this->saveAndTouchAvailability($sku, $quantity);

        return $spyAvailabilityEntity->getFkAvailabilityAbstract();
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function saveAndTouchAvailability($sku, $quantity)
    {
        $currentQuantity = $this->findCurrentPhysicalQuantity($sku);
        $spyAvailabilityEntity = $this->prepareAvailabilityEntityForSave($sku, $quantity);

        $isNeverOutOfStockModified = $spyAvailabilityEntity->isColumnModified(SpyAvailabilityTableMap::COL_IS_NEVER_OUT_OF_STOCK);

        $spyAvailabilityEntity->save();

        $this->updateAbstractAvailabilityQuantity($spyAvailabilityEntity->getFkAvailabilityAbstract());

        if ($this->isAvailabilityStatusChanged($currentQuantity, $quantity) || $isNeverOutOfStockModified) {
            $this->touchAvailabilityAbstract($spyAvailabilityEntity->getFkAvailabilityAbstract());
        }

        return $spyAvailabilityEntity;
    }

    /**
     * @param string $sku
     * @param string $quantity
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function prepareAvailabilityEntityForSave($sku, $quantity)
    {
        $spyAvailabilityEntity = $this->querySpyAvailabilityBySku($sku)
            ->findOneOrCreate();

        if ($spyAvailabilityEntity->isNew()) {
            $availabilityAbstractEntity = $this->findOrCreateSpyAvailabilityAbstract($sku);
            $spyAvailabilityEntity->setFkAvailabilityAbstract($availabilityAbstractEntity->getIdAvailabilityAbstract());
        }

        $spyAvailabilityEntity->setQuantity($quantity);
        $spyAvailabilityEntity->setIsNeverOutOfStock($this->stockFacade->isNeverOutOfStock($sku));

        return $spyAvailabilityEntity;
    }

    /**
     * @param int $currentQuantity
     * @param int $quantityWithReservedItems
     *
     * @return bool
     */
    protected function isAvailabilityStatusChanged($currentQuantity, $quantityWithReservedItems)
    {
        if ($currentQuantity === null && $quantityWithReservedItems !== null) {
            return true;
        }

        if ($currentQuantity === 0 && $quantityWithReservedItems > $currentQuantity) {
            return true;
        }

        if ($currentQuantity !== 0 && $quantityWithReservedItems === 0) {
            return true;
        }

        return false;
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery
     */
    protected function querySpyAvailabilityBySku($sku)
    {
        return $this->queryContainer
            ->querySpyAvailabilityBySku($sku);
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
     * @param int $quantity
     *
     * @return int
     */
    protected function getQuantity($quantity)
    {
        return $quantity > 0 ? $quantity : 0;
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    protected function findCurrentPhysicalQuantity($sku)
    {
        $oldQuantity = null;
        $availabilityEntity = $this->querySpyAvailabilityBySku($sku)
            ->findOne();

        if ($availabilityEntity !== null) {
            $oldQuantity = $availabilityEntity->getQuantity();
        }

        return $oldQuantity;
    }

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return void
     */
    public function updateAbstractAvailabilityQuantity($idAvailabilityAbstract)
    {
        $availabilityAbstractEntity = $this->queryContainer
            ->queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract)
            ->findOne();

        $sumQuantity = (int)$this->queryContainer
            ->querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract)
            ->findOne();

        $availabilityAbstractEntity->setQuantity($sumQuantity);
        $availabilityAbstractEntity->save();
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Availability\Business\Exception\ProductNotFoundException
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    protected function findOrCreateSpyAvailabilityAbstract($sku)
    {
        $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);

        if ($abstractSku === null) {
            throw new ProductNotFoundException(
                sprintf('The product was not found with this SKU: %s', $sku)
            );
        }

        $availabilityAbstractEntity = $this->queryContainer
            ->querySpyAvailabilityAbstractByAbstractSku($abstractSku)
            ->findOne();

        if ($availabilityAbstractEntity !== null) {
            return $availabilityAbstractEntity;
        }

        return $this->createSpyAvailabilityAbstract($abstractSku);
    }

    /**
     * @param string $abstractSku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    protected function createSpyAvailabilityAbstract($abstractSku)
    {
        $availableAbstractEntity = new SpyAvailabilityAbstract();
        $availableAbstractEntity->setAbstractSku($abstractSku);
        $availableAbstractEntity->save();

        return $availableAbstractEntity;
    }
}
