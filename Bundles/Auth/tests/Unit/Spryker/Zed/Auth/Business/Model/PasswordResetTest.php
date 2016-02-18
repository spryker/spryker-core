<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Unit\Spryker\Zed\Auth\Business\Model;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Business\Model\PasswordReset;
use Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge;
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
        $facadeUser = $this->createFacadeUser();
        $authConfig = new AuthConfig();

        $passwordReset = $this->createPasswordReset($authQueryContainer, $facadeUser, $authConfig);

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

    /**
     * @return \Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createFacadeUser()
    {
        $facadeUser = $this->getMock(
            AuthToUserBridge::class,
            ['getUserByUsername'],
            [new UserFacade()]
        );

        return $facadeUser;
    }

    /**
     * @param \Spryker\Zed\Auth\Persistence\AuthQueryContainer $authQueryContainer
     * @param \Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge $facadeUser
     * @param \Spryker\Zed\Auth\AuthConfig $authConfig
     *
     * @return \Spryker\Zed\Auth\Business\Model\PasswordReset|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createPasswordReset($authQueryContainer, $facadeUser, $authConfig)
    {
        $passwordReset = $this->getMock(
            PasswordReset::class,
            ['persistResetPassword'],
            [$authQueryContainer, $facadeUser, $authConfig]
        );

        return $passwordReset;
    }

}
