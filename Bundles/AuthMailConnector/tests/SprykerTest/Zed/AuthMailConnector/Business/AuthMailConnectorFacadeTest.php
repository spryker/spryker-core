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
        $authMailConnectorToMailBridgeMockBuilder = $this->getMockBuilder(AuthMailConnectorToMailBridge::class)
            ->disableOriginalConstructor()
            ->setMethods(['handleMail']);

        $authMailConnectorToMailBridgeMock = $authMailConnectorToMailBridgeMockBuilder->getMock();
        $authMailConnectorToMailBridgeMock
            ->expects($this->once())
            ->method('handleMail');

        $authMailConnectorFacade = $this->createAuthMailConnectorFacade($authMailConnectorToMailBridgeMock);

        $authMailConnectorFacade->sendResetPasswordMail(static::EMAIL, static::TOKEN);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $authMailConnectorToMailBridgeMock
     *
     * @return \Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorFacadeInterface
     */
    protected function createAuthMailConnectorFacade(MockObject $authMailConnectorToMailBridgeMock): AuthMailConnectorFacadeInterface
    {
        /** @var \Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorFacadeInterface $authMailConnectorFacade */
        $authMailConnectorFacade = $this->tester->getFacade();
        $authMailConnectorFacade->setFactory($this->createAuthMailConnectorBusinessFactoryMock($authMailConnectorToMailBridgeMock));

        return $authMailConnectorFacade;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $authMailConnectorToMailBridgeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAuthMailConnectorBusinessFactoryMock(MockObject $authMailConnectorToMailBridgeMock): MockObject
    {
        $authMailConnectorBusinessFactoryMockBuilder = $this->getMockBuilder(AuthMailConnectorBusinessFactory::class)
            ->setMethods(['getMailFacade', 'getConfig']);

        $authMailConnectorBusinessFactoryMock = $authMailConnectorBusinessFactoryMockBuilder
            ->getMock();
        $authMailConnectorBusinessFactoryMock
            ->method('getMailFacade')
            ->willReturn($authMailConnectorToMailBridgeMock);

        $authMailConnectorBusinessFactoryMock
            ->method('getConfig')
            ->willReturn(new AuthMailConnectorConfig());

        return $authMailConnectorBusinessFactoryMock;
    }
}
