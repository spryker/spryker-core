<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeServicePoint;

use Codeception\Actor;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ShipmentTypeServicePoint\PHPMD)
 *
 * @method \Spryker\Zed\ShipmentTypeServicePoint\Business\ShipmentTypeServicePointFacadeInterface getFacade(?string $moduleName = null)
 */
class ShipmentTypeServicePointBusinessTester extends Actor
{
    use _generated\ShipmentTypeServicePointBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureShipmentTypeServiceTypeTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getShipmentTypeServiceTypeQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     * @param list<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return void
     */
    public function createShipmentTypesServiceTypeRelations(ServiceTypeTransfer $serviceTypeTransfer, array $shipmentTypeTransfers): void
    {
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $this->haveShipmentTypeServiceTypeRelation($shipmentTypeTransfer, $serviceTypeTransfer);
        }
    }

    /**
     * @return \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery
     */
    protected function getShipmentTypeServiceTypeQuery(): SpyShipmentTypeServiceTypeQuery
    {
        return SpyShipmentTypeServiceTypeQuery::create();
    }
}
