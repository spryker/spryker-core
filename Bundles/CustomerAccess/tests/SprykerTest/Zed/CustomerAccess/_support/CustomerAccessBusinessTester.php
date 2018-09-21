<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerAccess;

use Codeception\Actor;
use Spryker\Zed\CustomerAccess\Business\CustomerAccessFacadeInterface;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CustomerAccessBusinessTester extends Actor
{
    use _generated\CustomerAccessBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return \Spryker\Zed\CustomerAccess\Business\CustomerAccessFacadeInterface
     */
    public function getFacade(): CustomerAccessFacadeInterface
    {
        return $this->getLocator()->customerAccess()->facade();
    }
}
