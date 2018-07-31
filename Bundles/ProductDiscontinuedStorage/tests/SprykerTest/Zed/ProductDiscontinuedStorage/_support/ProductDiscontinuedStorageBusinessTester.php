<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedStorage;

use Codeception\Actor;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductDiscontinuedStorageBusinessTester extends Actor
{
    use _generated\ProductDiscontinuedStorageBusinessTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinuedFacade(): ProductDiscontinuedFacadeInterface
    {
        return $this->getLocator()->productDiscontinued()->facade();
    }
}
