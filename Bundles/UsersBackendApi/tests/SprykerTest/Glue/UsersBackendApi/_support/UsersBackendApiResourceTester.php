<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\UsersBackendApi;

use Codeception\Actor;
use Spryker\Glue\UsersBackendApi\UsersBackendApiResourceInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Glue\UsersBackendApi\PHPMD)
 */
class UsersBackendApiResourceTester extends Actor
{
    use _generated\UsersBackendApiResourceTesterActions;

    /**
     * @return \Spryker\Glue\UsersBackendApi\UsersBackendApiResourceInterface
     */
    public function getResource(): UsersBackendApiResourceInterface
    {
        return $this->getLocator()->usersBackendApi()->resource();
    }
}
