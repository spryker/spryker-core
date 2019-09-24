<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Agent;

use Codeception\Actor;
use Spryker\Zed\Agent\Business\AgentFacadeInterface;

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
class AgentBusinessTester extends Actor
{
    use _generated\AgentBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return \Spryker\Zed\Agent\Business\AgentFacadeInterface
     */
    public function getAgentFacade(): AgentFacadeInterface
    {
        /** @var \Spryker\Zed\Agent\Business\AgentFacadeInterface $facade */
        $facade = $this->getFacade();

        return $facade;
    }
}
