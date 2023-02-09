<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseAllocation;

use Codeception\Actor;
use Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocationQuery;
use Propel\Runtime\Collection\ObjectCollection;

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
 * @method void pause($vars = [])
 * @method \Spryker\Zed\WarehouseAllocation\Business\WarehouseAllocationFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class WarehouseAllocationBusinessTester extends Actor
{
    use _generated\WarehouseAllocationBusinessTesterActions;

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation>
     */
    public function getWarehouseAllocations(): ObjectCollection
    {
        return $this->getWarehouseAllocationQuery()->find();
    }

    /**
     * @return \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocationQuery<\Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation>
     */
    protected function getWarehouseAllocationQuery(): SpyWarehouseAllocationQuery
    {
        return SpyWarehouseAllocationQuery::create();
    }
}
