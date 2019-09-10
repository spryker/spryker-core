<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthRestApi\Business;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\AuthRestApi\Business\AuthRestApiBusinessFactory;
use Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeBridge;
use Spryker\Zed\Oauth\Business\OauthFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group AuthRestApi
 * @group Business
 * @group Facade
 * @group AuthRestApiFacadeTest
 * Add your own group annotations below this line
 */
class AuthRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AuthRestApi\AuthRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProcessAccessTokenWillGetValidOauthResponseTransfer(): void
    {
        $authRestApiFacade = $this->tester->getFacade();
        $authRestApiFacade->setFactory($this->getMockAuthRestApiBusinessFactory());
        $oauthRequestTransfer = $this->tester->prepareOauthRequestTransfer();

        $oauthResponseTransfer = $authRestApiFacade->processAccessToken($oauthRequestTransfer);

        $this->assertEquals($oauthResponseTransfer->getAnonymousCustomerReference(), $oauthRequestTransfer->getCustomerReference());
        $this->assertTrue($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testProcessAccessTokenWillGetInvalidOauthResponseTransfer(): void
    {
        $authRestApiFacade = $this->tester->getFacade();
        $authRestApiFacade->setFactory($this->getMockAuthRestApiBusinessFactoryWithInvalidProcessAccessTokenRequest());
        $oauthRequestTransfer = $this->tester->prepareOauthRequestTransfer();

        $oauthResponseTransfer = $authRestApiFacade->processAccessToken($oauthRequestTransfer);
        $this->assertFalse($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockAuthRestApiBusinessFactory(): MockObject
    {
        $authRestApiBusinessFactoryMock = $this->createPartialMock(
            AuthRestApiBusinessFactory::class,
            [
                'getOauthFacade',
                'getPostAuthPlugins',
            ]
        );

        $authRestApiBusinessFactoryMock = $this->addMockOauthFacade($authRestApiBusinessFactoryMock);

        return $authRestApiBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockAuthRestApiBusinessFactoryWithInvalidProcessAccessTokenRequest(): MockObject
    {
        $authRestApiBusinessFactoryMock = $this->createPartialMock(
            AuthRestApiBusinessFactory::class,
            [
                'getOauthFacade',
                'getPostAuthPlugins',
            ]
        );

        $authRestApiBusinessFactoryMock = $this->addMockOauthFacadeWithInvalidProcessAccessTokenRequest($authRestApiBusinessFactoryMock);

        return $authRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $authRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockOauthFacade(MockObject $authRestApiBusinessFactoryMock): MockObject
    {
        $oauthFacadeMock = $this->createPartialMock(
            OauthFacade::class,
            [
                'processAccessTokenRequest',
            ]
        );

        $oauthFacadeMock->method('processAccessTokenRequest')
            ->willReturn($this->tester->prepareOauthResponseTransfer());

        $authRestApiBusinessFactoryMock->method('getOauthFacade')
            ->willReturn((new AuthRestApiToOauthFacadeBridge($oauthFacadeMock)));

        return $authRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $authRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockOauthFacadeWithInvalidProcessAccessTokenRequest(MockObject $authRestApiBusinessFactoryMock): MockObject
    {
        $oauthFacadeMock = $this->createPartialMock(
            OauthFacade::class,
            [
                'processAccessTokenRequest',
            ]
        );

        $oauthFacadeMock->method('processAccessTokenRequest')
            ->willReturn($this->tester->prepareInvalidOauthResponseTransfer());

        $authRestApiBusinessFactoryMock->method('getOauthFacade')
            ->willReturn((new AuthRestApiToOauthFacadeBridge($oauthFacadeMock)));

        return $authRestApiBusinessFactoryMock;
    }
}
