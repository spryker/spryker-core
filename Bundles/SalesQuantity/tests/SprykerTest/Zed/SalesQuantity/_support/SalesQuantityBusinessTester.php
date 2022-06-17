<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesQuantity;

use Codeception\Actor;
use Orm\Zed\Discount\Persistence\SpyDiscount;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\SalesQuantity\Business\SalesQuantityFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesQuantityBusinessTester extends Actor
{
    use _generated\SalesQuantityBusinessTesterActions;

    /**
     * @return bool
     */
    public function discountPriorityFieldExists(): bool
    {
        return property_exists(SpyDiscount::class, 'priority');
    }
}
