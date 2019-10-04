<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Price;

use Codeception\Actor;
use Spryker\Client\Price\PriceModeCache\PriceModeCache;
use Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceClientTester extends Actor
{
    use _generated\PriceClientTesterActions;

    /**
     * @return \Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface
     */
    public function createPriceModeCache(): PriceModeCacheInterface
    {
        return new PriceModeCache();
    }
}
