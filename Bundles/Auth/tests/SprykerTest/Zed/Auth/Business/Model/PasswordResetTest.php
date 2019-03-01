<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Auth\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Business\Model\PasswordReset;
use Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge;
use Spryker\Zed\Auth\Persistence\AuthQueryContainer;
use Spryker\Zed\User\Business\UserFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Auth
 * @group Business
 * @group Model
 * @group PasswordResetTest
 * Add your own group annotations below this line
 */
class PasswordResetTest extends Unit
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
     * @return \Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createFacadeUser()
    {
        $userFacade = $this->getMockBuilder(AuthToUserBridge::class)->setMethods(['getUserByUsername'])->setConstructorArgs([new UserFacade()])->getMock();

        return $userFacade;
    }

    /**
     * @param \Spryker\Zed\Auth\Persistence\AuthQueryContainer $authQueryContainer
     * @param \Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge $userFacade
     * @param \Spryker\Zed\Auth\AuthConfig $authConfig
     *
     * @return \Spryker\Zed\Auth\Business\Model\PasswordReset|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPasswordReset($authQueryContainer, $userFacade, $authConfig)
    {
        $passwordReset = $this->getMockBuilder(PasswordReset::class)->setMethods(['persistResetPassword'])->setConstructorArgs([$authQueryContainer, $userFacade, $authConfig])->getMock();

        return $passwordReset;
    }
}
