<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthAgentConnector;

use Codeception\Actor;
use Spryker\Zed\User\Business\UserFacadeInterface;

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
 * @method \Spryker\Zed\OauthAgentConnector\Business\OauthAgentConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class OauthAgentConnectorBusinessTester extends Actor
{
    use _generated\OauthAgentConnectorBusinessTesterActions;

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    public function getUserFacade(): UserFacadeInterface
    {
        return $this->getLocator()->user()->facade();
    }
}
