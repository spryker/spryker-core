<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CartReorder;

use Codeception\Actor;
use Spryker\Client\CartReorder\CartReorderClientInterface;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class CartReorderClientTester extends Actor
{
    use _generated\CartReorderClientTesterActions;

    /**
     * @return \Spryker\Client\CartReorder\CartReorderClientInterface
     */
    public function getClient(): CartReorderClientInterface
    {
        return $this->getLocator()->cartReorder()->client();
    }
}
