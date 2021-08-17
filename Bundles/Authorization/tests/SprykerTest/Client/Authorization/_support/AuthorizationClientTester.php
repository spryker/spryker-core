<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Authorization;

use Codeception\Actor;
use Spryker\Client\Authorization\AuthorizationClientInterface;

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
class AuthorizationClientTester extends Actor
{
    use _generated\AuthorizationClientTesterActions;

    /**
     * @return \Spryker\Client\Authorization\AuthorizationClientInterface
     */
    public function getClient(): AuthorizationClientInterface
    {
        return $this->getLocator()->authorization()->client();
    }
}
