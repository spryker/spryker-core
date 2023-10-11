<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeServicePointStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ShipmentTypeServicePointStorage\Business\ShipmentTypeServicePointStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ShipmentTypeServicePointStorage\PHPMD)
 */
class ShipmentTypeServicePointStorageBusinessTester extends Actor
{
    use _generated\ShipmentTypeServicePointStorageBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureShipmentTypeServiceTypeTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getShipmentTypeServiceTypeQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function haveShipmentTypeWithServiceTypeRelation(ServiceTypeTransfer $serviceTypeTransfer): ShipmentTypeTransfer
    {
        $shipmentTypeTransfer = $this->haveShipmentType();
        $this->haveShipmentTypeServiceTypeRelation($shipmentTypeTransfer, $serviceTypeTransfer);

        return $shipmentTypeTransfer;
    }

    /**
     * @return \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery
     */
    protected function getShipmentTypeServiceTypeQuery(): SpyShipmentTypeServiceTypeQuery
    {
        return SpyShipmentTypeServiceTypeQuery::create();
    }
}
