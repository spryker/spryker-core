<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Orm\Zed\Availability\Persistence\SpyAvailability;
use Spryker\Shared\Availability\AvailabilityConstants;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;

class AvailabilityHandler implements AvailabilityHandlerInterface
{

    /**
     * @var SellableInterface
     */
    protected $sellable;

    /**
     * @var AvailabilityToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var AvailabilityQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param SellableInterface $sellable
     * @param AvailabilityToTouchInterface $touchFacade
     * @param AvailabilityQueryContainerInterface $queryContainer
     */
    public function __construct(
        SellableInterface $sellable,
        AvailabilityToTouchInterface $touchFacade,
        AvailabilityQueryContainerInterface $queryContainer
    )
    {
        $this->sellable = $sellable;
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
            $this->touchAvailability($savedAvailabilityEntity->getIdAvailability());
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
     * @return SpyAvailability
     */
    protected function saveCurrentAvailability($sku, $quantity)
    {
        $spyAvailability = $this->querySpyAvailabilityBySku($sku)->findOneOrCreate();
        $spyAvailability->setQuantity($quantity);
        $spyAvailability->save();

        return $spyAvailability;
    }

    /**
     * @param $sku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    protected function querySpyAvailabilityBySku($sku)
    {
        return  $this->queryContainer->querySpyAvailabilityBySku($sku);
    }

    /**
     * @param int
     *
     * @return void
     */
    protected function touchAvailability($idAvailability)
    {
        $this->touchFacade->touchActive(AvailabilityConstants::RESOURCE_TYPE_AVAILABILITY, $idAvailability);
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
        $availabilityEntity = $this->querySpyAvailabilityBySku($sku)->findOne();

        if ($availabilityEntity !== null) {
            $oldQuantity = $availabilityEntity->getQuantity();
        }

        return $oldQuantity;
    }

}
