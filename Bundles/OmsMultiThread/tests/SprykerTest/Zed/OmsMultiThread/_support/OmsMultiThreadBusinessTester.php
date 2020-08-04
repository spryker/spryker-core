<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OmsMultiThread;

use Codeception\Actor;
use Spryker\Zed\OmsMultiThread\Business\OmsMultiThreadFacadeInterface;

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
 * @method \Spryker\Zed\OmsMultiThread\Business\OmsMultiThreadFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class OmsMultiThreadBusinessTester extends Actor
{
    use _generated\OmsMultiThreadBusinessTesterActions;

    /**
     * @return \Spryker\Zed\OmsMultiThread\Business\OmsMultiThreadFacadeInterface
     */
    public function getOmsMultiThreadFacade(): OmsMultiThreadFacadeInterface
    {
        return $this->getFacade();
    }
}
