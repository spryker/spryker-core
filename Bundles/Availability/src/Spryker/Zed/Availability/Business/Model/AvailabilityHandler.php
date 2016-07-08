<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Orm\Zed\Availability\Persistence\SpyAvailability;
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
        $currentQuantity = null;
        $currentAvailabilityEntity = $this->querySpyAvailabilityBySku($sku)->findOne();

        if ($currentAvailabilityEntity !== null) {
            $currentQuantity = $currentAvailabilityEntity->getQuantity();
        }

        $newAvailabilityQuantity = $this->sellable->calculateStockForProduct($sku);
        $currentAvailabilityEntity = $this->saveCurrentAvailability($sku, $newAvailabilityQuantity);

        if ($currentQuantity === null || $this->isAvailabilityStatusChanged($currentQuantity, $newAvailabilityQuantity)) {
            $this->touchAvailability($currentAvailabilityEntity->getIdAvailability());
        }
    }

    /**
     * @param int $currentQuantity
     * @param int $newQuantity
     *
     * @return bool
     */
    protected function isAvailabilityStatusChanged($currentQuantity, $newQuantity)
    {
        if ($currentQuantity === 0 && $newQuantity > $currentQuantity) {
            return true;
        }

        if ($currentQuantity !== 0 && $newQuantity === 0) {
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
        $this->touchFacade->touchActive('availability', $idAvailability);
    }

}
