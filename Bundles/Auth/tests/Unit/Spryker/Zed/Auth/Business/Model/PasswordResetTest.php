<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Unit\Spryker\Zed\Auth\Business\Model;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Persistence\AuthQueryContainer;
use Spryker\Zed\User\Business\UserFacade;

/**
 * @group Auth
 * @group Business
 * @group Model
 * @group PasswordReset
 */
class PasswordResetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRequestToken()
    {
        $authQueryContainer = new AuthQueryContainer();
        /** @var \Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge $facadeUser */
        $facadeUser = $this->getMock(
            'Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge',
            ['getUserByUsername'],
            [new UserFacade()]
        );
        $authConfig = new AuthConfig();

        $passwordReset = $this->getMock(
            'Spryker\Zed\Auth\Business\Model\PasswordReset',
            ['persistResetPassword'],
            [$authQueryContainer, $facadeUser, $authConfig]
        );

        $userTransfer = new UserTransfer();

        $facadeUser->expects($this->once())
            ->method('getUserByUsername')
            ->will($this->returnValue($userTransfer));

        $passwordReset->expects($this->once())
            ->method('persistResetPassword')
            ->will($this->returnValue(true));

        $result = $passwordReset->requestToken('foo@bar.de');
        $this->assertTrue($result);
    }

}
