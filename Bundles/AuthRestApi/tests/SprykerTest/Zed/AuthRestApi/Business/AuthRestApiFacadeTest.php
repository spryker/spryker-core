<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthResponseTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
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
     * @var \Spryker\Zed\AuthRestApi\Business\AuthRestApiFacadeInterface
     */
    protected $authRestApiFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->authRestApiFacade = $this->tester->getFacade();
        $this->authRestApiFacade->setFactory($this->getMockAuthRestApiBusinessFactory());
    }

    /**
     * @return void
     */
    public function testProcessAccessTokenWillProcessAccessToken(): void
    {
        $oauthRequestTransfer = $this->tester->prepareOauthRequestTransfer();

        $oauthResponseTransfer = $this->authRestApiFacade->processAccessToken($oauthRequestTransfer);

        $this->assertInstanceOf(OauthResponseTransfer::class, $oauthResponseTransfer);
        $this->assertEquals($oauthResponseTransfer->getCustomerReference(), $this->tester::TEST_CUSTOMER_REFERENCE);
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
}
