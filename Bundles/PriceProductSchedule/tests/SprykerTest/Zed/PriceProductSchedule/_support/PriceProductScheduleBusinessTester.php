<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule;

use Codeception\Actor;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceProductScheduleBusinessTester extends Actor
{
    use _generated\PriceProductScheduleBusinessTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $priceProductScheduleQuery = $this->getPriceProductScheduleQuery();

        $priceProductScheduleQuery->deleteAll();
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    public function getPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return SpyPriceProductScheduleQuery::create();
    }
}
