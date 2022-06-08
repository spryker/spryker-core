<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Discount;

use Codeception\Actor;
use Spryker\Service\Discount\DiscountServiceInterface;

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
 * @SuppressWarnings(PHPMD)
 */
class DiscountServiceTester extends Actor
{
    use _generated\DiscountServiceTesterActions;

    /**
     * @return \Spryker\Service\Discount\DiscountServiceInterface
     */
    public function getDiscountService(): DiscountServiceInterface
    {
        return $this->getLocator()->discount()->service();
    }
}
