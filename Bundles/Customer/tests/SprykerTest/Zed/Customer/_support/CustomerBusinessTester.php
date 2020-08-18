<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer;

use Codeception\Actor;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 */
class CustomerBusinessTester extends Actor
{
    use _generated\CustomerBusinessTesterActions;

    /**
     * @param string $hash
     * @param string $rowPassword
     * @param string $salt
     *
     * @return void
     */
    public function assertPasswordsEqual(string $hash, string $rowPassword, string $salt = ''): void
    {
        $nativePasswordEncoder = new NativePasswordEncoder();

        $this->assertTrue($nativePasswordEncoder->isPasswordValid($hash, $rowPassword, $salt), 'Passwords are now equal.');
    }
}
