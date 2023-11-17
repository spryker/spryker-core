<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability;

use Codeception\Actor;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Availability\Persistence\SpyAvailabilityQuery;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface getFacade()
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\Availability\PHPMD)
 */
class AvailabilityBusinessTester extends Actor
{
    use _generated\AvailabilityBusinessTesterActions;

    /**
     * @param string $productSku
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability|null
     */
    public function findAvailabilityConcreteForStore(string $productSku, int $idStore): ?SpyAvailability
    {
        return $this->getAvailabilityQuery()
            ->filterBySku($productSku)
            ->filterByFkStore($idStore)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    public function getAvailabilityQuery(): SpyAvailabilityQuery
    {
        return SpyAvailabilityQuery::create();
    }

    /**
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function getAvailabilityAbstractQuery(): SpyAvailabilityAbstractQuery
    {
        return SpyAvailabilityAbstractQuery::create();
    }
}
