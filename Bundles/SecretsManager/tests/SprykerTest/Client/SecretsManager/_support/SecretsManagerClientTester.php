<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecretsManager;

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
 * @method \Spryker\Client\SecretsManager\SecretsManagerClientInterface getClient()
 * @method \Spryker\Client\SecretsManager\SecretsManagerFactory getFactory()
 * @SuppressWarnings(PHPMD)
 */
class SecretsManagerClientTester extends Actor
{
    use _generated\SecretsManagerClientTesterActions;
}
