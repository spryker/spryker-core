<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Auth\Business\Model;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Business\Model\PasswordReset;
use Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge;
use Spryker\Zed\Auth\Persistence\AuthQueryContainer;
use Spryker\Zed\User\Business\UserFacade;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Auth
 * @group Business
 * @group Model
 * @group PasswordResetTest
 */
class PasswordResetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRequestToken()
    {
        $authQueryContainer = new AuthQueryContainer();
        $userFacade = $this->createFacadeUser();
        $authConfig = new AuthConfig();

        $passwordReset = $this->createPasswordReset($authQueryContainer, $userFacade, $authConfig);

        $userTransfer = new UserTransfer();

        $userFacade->expects($this->once())
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
        $userFacade = $this->getMock(
            AuthToUserBridge::class,
            ['getUserByUsername'],
            [new UserFacade()]
        );

        return $userFacade;
    }

    /**
     * @param \Spryker\Zed\Auth\Persistence\AuthQueryContainer $authQueryContainer
     * @param \Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge $userFacade
     * @param \Spryker\Zed\Auth\AuthConfig $authConfig
     *
     * @return \Spryker\Zed\Auth\Business\Model\PasswordReset|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createPasswordReset($authQueryContainer, $userFacade, $authConfig)
    {
        $passwordReset = $this->getMock(
            PasswordReset::class,
            ['persistResetPassword'],
            [$authQueryContainer, $userFacade, $authConfig]
        );

        return $passwordReset;
    }

}
