<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerAccessStorage;

use Codeception\Actor;
use Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessStorageFacadeInterface;

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
class CustomerAccessStorageBusinessTester extends Actor
{
    use _generated\CustomerAccessStorageBusinessTesterActions;

    /**
     * @return \Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessStorageFacadeInterface
     */
    public function getFacade(): CustomerAccessStorageFacadeInterface
    {
        return $this->getLocator()->customerAccessStorage()->facade();
    }
}
