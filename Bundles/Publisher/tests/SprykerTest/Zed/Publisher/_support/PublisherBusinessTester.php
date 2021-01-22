<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher;

use Codeception\Actor;

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
 * @method \Spryker\Zed\Publisher\Business\[PublisherBusinessFactory] getFactory()
 * @method \Spryker\Zed\Publisher\Business\PublisherFacadeInterface getFacade()
 * @method \Spryker\Zed\Publisher\PublisherConfig getModuleConfig()
 *
 * @SuppressWarnings(PHPMD)
 */
class PublisherBusinessTester extends Actor
{
    use _generated\PublisherBusinessTesterActions;
}
