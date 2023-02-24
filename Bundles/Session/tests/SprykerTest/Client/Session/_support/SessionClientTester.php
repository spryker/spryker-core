<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Client\Session;

use Codeception\Actor;
use Spryker\Client\Session\SessionClientInterface;

/**
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
 * @SuppressWarnings(PHPMD)
 */
class SessionClientTester extends Actor
{
    use _generated\SessionClientTesterActions;

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getClient(): SessionClientInterface
    {
        return $this->getLocator()->session()->client();
    }
}
