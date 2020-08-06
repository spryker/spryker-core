<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthMailConnector\Business;

use Codeception\Test\Unit;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig;
use Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorBusinessFactory;
use Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorFacadeInterface;
use Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailBridge;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AuthMailConnector
 * @group Business
 * @group Facade
 * @group AuthMailConnectorFacadeTest
 * Add your own group annotations below this line
 */
class AuthMailConnectorFacadeTest extends Unit
{
    protected const EMAIL = 'test@test.com';
    protected const TOKEN = 'token';

    /**
     * @var \SprykerTest\Zed\AuthMailConnector\AuthMailConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSendResetPasswordMailTriggerMailFacadeHandleMailMethodOnce(): void
    {
        $authMailConnectorToMailBridgeMock = $this->getMockBuilder(AuthMailConnectorToMailBridge::class)
            ->disableOriginalConstructor()
            ->setMethods(['handleMail'])
            ->getMock();

        $authMailConnectorToMailBridgeMock
            ->expects($this->once())
            ->method('handleMail');

        $authMailConnectorFacade = $this->createAuthMailConnectorFacade($authMailConnectorToMailBridgeMock);

        $authMailConnectorFacade->sendResetPasswordMail(static::EMAIL, static::TOKEN);
    }

    /**
     * @param \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailBridge|\PHPUnit\Framework\MockObject\MockObject $authMailConnectorToMailBridgeMock
     *
     * @return \Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorFacadeInterface
     */
    protected function createAuthMailConnectorFacade(AuthMailConnectorToMailBridge $authMailConnectorToMailBridgeMock): AuthMailConnectorFacadeInterface
    {
        /** @var \Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorFacade $authMailConnectorFacade */
        $authMailConnectorFacade = $this->tester->getFacade();
        $authMailConnectorFacade->setFactory($this->createAuthMailConnectorBusinessFactoryMock($authMailConnectorToMailBridgeMock));

        return $authMailConnectorFacade;
    }

    /**
     * @param \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailBridge|\PHPUnit\Framework\MockObject\MockObject $authMailConnectorToMailBridgeMock
     *
     * @return \Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAuthMailConnectorBusinessFactoryMock(
        AuthMailConnectorToMailBridge $authMailConnectorToMailBridgeMock
    ): AuthMailConnectorBusinessFactory {
        $authMailConnectorBusinessFactoryMock = $this->getMockBuilder(AuthMailConnectorBusinessFactory::class)
            ->setMethods(['getMailFacade', 'getConfig', 'getAuthMailExpanderPlugins'])
            ->getMock();

        $authMailConnectorBusinessFactoryMock
            ->method('getMailFacade')
            ->willReturn($authMailConnectorToMailBridgeMock);

        $authMailConnectorBusinessFactoryMock
            ->method('getConfig')
            ->willReturn(new AuthMailConnectorConfig());

        $authMailConnectorBusinessFactoryMock
            ->method('getAuthMailExpanderPlugins')
            ->willReturn([]);

        return $authMailConnectorBusinessFactoryMock;
    }
}
