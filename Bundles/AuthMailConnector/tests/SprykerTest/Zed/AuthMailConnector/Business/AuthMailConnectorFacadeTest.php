<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthMailConnector\Business;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig;
use Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorBusinessFactory;
use Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorFacade;
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
     * @return void
     */
    public function testSendResetPasswordMailTriggerMailFacadeHandleMailMethodOnce()
    {
        $authMailConnectorFacade = $this->createAuthMailConnectorFacade();
        $authMailConnectorFacade->sendResetPasswordMail(static::EMAIL, static::TOKEN);
    }

    /**
     * @return \Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorFacadeInterface
     */
    protected function createAuthMailConnectorFacade(): AuthMailConnectorFacadeInterface
    {
        $authMailConnectorFacade = new AuthMailConnectorFacade();
        $authMailConnectorFacade->setFactory($this->createAuthMailConnectorBusinessFactoryMock());

        return $authMailConnectorFacade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAuthMailConnectorBusinessFactoryMock(): MockObject
    {
        $authMailConnectorBusinessFactoryMockBuilder = $this->getMockBuilder(AuthMailConnectorBusinessFactory::class)
            ->setMethods(['getMailFacade', 'getConfig']);

        $authMailConnectorBusinessFactoryMock = $authMailConnectorBusinessFactoryMockBuilder->getMock();
        $authMailConnectorBusinessFactoryMock
            ->method('getMailFacade')
            ->willReturn($this->createAuthMailConnectorToMailBridgeMock());

        $authMailConnectorBusinessFactoryMock
            ->method('getConfig')
            ->willReturn($this->createAuthMailConnectorConfigMock());

        return $authMailConnectorBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAuthMailConnectorToMailBridgeMock(): MockObject
    {
        $authMailConnectorToMailBridgeMockBuilder = $this->getMockBuilder(AuthMailConnectorToMailBridge::class)
            ->disableOriginalConstructor()
            ->setMethods(['handleMail']);

        $authMailConnectorToMailBridgeMock = $authMailConnectorToMailBridgeMockBuilder->getMock();
        $authMailConnectorToMailBridgeMock
            ->expects($this->once())
            ->method('handleMail');

        return $authMailConnectorToMailBridgeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAuthMailConnectorConfigMock(): MockObject
    {
        $authMailConnectorConfigMock = $this->getMockBuilder(AuthMailConnectorConfig::class)
            ->getMock();

        return $authMailConnectorConfigMock;
    }
}
