<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentsBackendApi;

use Codeception\Actor;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Glue\ShipmentsBackendApi\ShipmentsBackendApiResourceInterface;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentsBackendApiResourceTester extends Actor
{
    use _generated\ShipmentsBackendApiResourceTesterActions;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     * @param int $idSalesShipment
     *
     * @return void
     */
    public function updateSalesOrderItemsWithIdShipment(ObjectCollection $salesOrderItemEntities, int $idSalesShipment): void
    {
        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $salesOrderItemEntity->setFkSalesShipment($idSalesShipment);
            $salesOrderItemEntity->save();
        }
    }

    /**
     * @return \Spryker\Glue\ShipmentsBackendApi\ShipmentsBackendApiResourceInterface
     */
    public function getResource(): ShipmentsBackendApiResourceInterface
    {
        return $this->getLocator()->shipmentsBackendApi()->resource();
    }
}
