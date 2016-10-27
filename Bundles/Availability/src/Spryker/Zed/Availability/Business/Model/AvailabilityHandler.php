<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Spryker\Shared\Availability\AvailabilityConstants;
use Spryker\Zed\Availability\Business\Exception\ProductNotFoundException;
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
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface $touchFacade
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface $queryContainer
     */
    public function __construct(
        SellableInterface $sellable,
        AvailabilityToStockInterface $stockFacade,
        AvailabilityToTouchInterface $touchFacade,
        AvailabilityQueryContainerInterface $queryContainer
    ) {

        $this->sellable = $sellable;
        $this->stockFacade = $stockFacade;
        $this->touchFacade = $touchFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku)
    {
        $oldQuantity = $this->getOldPhysicalQuantity($sku);
        $newQuantity = $this->getNewPhysicalQuantity($this->sellable->calculateStockForProduct($sku));

        $savedAvailabilityEntity = $this->saveCurrentAvailability($sku, $newQuantity);

        if ($this->isAvailabilityStatusChanged($oldQuantity, $newQuantity)) {
            $this->touchAvailabilityAbstract($savedAvailabilityEntity->getFkAvailabilityAbstract());
        }
    }

    /**
     * @param int $oldQuantity
     * @param int $newQuantity
     *
     * @return bool
     */
    protected function isAvailabilityStatusChanged($oldQuantity, $newQuantity)
    {
        if ($oldQuantity === null) {
            return true;
        }

        if ($oldQuantity === 0 && $newQuantity > $oldQuantity) {
            return true;
        }

        if ($oldQuantity !== 0 && $newQuantity === 0) {
            return true;
        }

        return false;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function saveCurrentAvailability($sku, $quantity)
    {
        $spyAvailability = $this->querySpyAvailabilityBySku($sku)
            ->findOneOrCreate();

        if ($spyAvailability->isNew()) {
            $availabilityAbstractEntity = $this->findOrCreateSpyAvailabilityAbstract($sku);
            $spyAvailability->setFkAvailabilityAbstract($availabilityAbstractEntity->getIdAvailabilityAbstract());
        }

        $spyAvailability->setQuantity($quantity);
        $spyAvailability->setIsNeverOutOfStock($this->stockFacade->isNeverOutOfStock($sku));
        $spyAvailability->save();

        $this->updateAbstractAvailabilityQuantity($spyAvailability->getFkAvailabilityAbstract());

        return $spyAvailability;
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
     * @param int
     *
     * @return void
     */
    protected function touchAvailabilityAbstract($idAvailabilityAbstract)
    {
        $this->touchFacade->touchActive(AvailabilityConstants::RESOURCE_TYPE_AVAILABILITY_ABSTRACT, $idAvailabilityAbstract);
    }

    /**
     * @param int $quantity
     *
     * @return int
     */
    protected function getNewPhysicalQuantity($quantity)
    {
        return $quantity > 0 ? $quantity : 0;
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    protected function getOldPhysicalQuantity($sku)
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

        $sumQuantity = (int)$this->queryContainer->querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract)
            ->findOne();

        $availabilityAbstractEntity->setQuantity($sumQuantity);
        $availabilityAbstractEntity->save();
    }

    /**
     * @param $sku
     *
     * @throws \Spryker\Zed\Availability\Business\Exception\ProductNotFoundException
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    protected function findOrCreateSpyAvailabilityAbstract($sku)
    {
        $productEntity = $this->queryContainer->querySpyProductBySku($sku)->findOne();

        if ($productEntity === null) {
            throw new ProductNotFoundException(
                sprintf('The product was not found with this SKU: %s', $sku)
            );
        }

        $abstractSku = $productEntity->getAbstractSku();
        $availabilityAbstractEntity = $this->queryContainer->querySpyAvailabilityAbstractByAbstractSku($abstractSku)->findOne();

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
