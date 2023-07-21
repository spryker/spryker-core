<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker;

use Codeception\Actor;

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
 * @SuppressWarnings(\SprykerTest\Zed\MessageBroker\PHPMD)
 *
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade(?string $moduleName = null)
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory getFactory(?string $moduleName = null)
 */
class MessageBrokerBusinessTester extends Actor
{
    use _generated\MessageBrokerBusinessTesterActions;
}
