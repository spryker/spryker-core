<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AuthRestApi\Controller;

use Spryker\Glue\AuthRestApi\AuthRestApiFactory;
use Spryker\Glue\AuthRestApi\Controller\TokenResourceController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group AuthRestApi
 * @group Controller
 * @group TokenResourceControllerTest
 * Add your own group annotations below this line
 */
class TokenResourceControllerTest extends AbstractControllerTest
{
    /**
     * @return void
     */
    public function testPostActionAddsFailedLoginAuditLogWhenGrantTypeIsNotCorrect(): void
    {
        // Arrange
        $tokenResourceControllerMock = $this->getTokenResourceControllerMock('Failed Login', true);

        // Act
        $tokenResourceControllerMock->postAction(new Request());
    }

    /**
     * @return void
     */
    public function testPostActionAddsFailedLoginAuditLogWhenOauthResponseIsNotValid(): void
    {
        // Arrange
        $tokenResourceControllerMock = $this->getTokenResourceControllerMock('Failed Login', false);

        // Act
        $tokenResourceControllerMock->postAction(new Request([], ['grant_type' => 'password']));
    }

    /**
     * @return void
     */
    public function testPostActionAddsSuccessfulLoginAuditLogOnSuccessfulLogin(): void
    {
        // Arrange
        $tokenResourceControllerMock = $this->getTokenResourceControllerMock('Successful Login', true);

        // Act
        $tokenResourceControllerMock->postAction(new Request([], ['grant_type' => 'password']));
    }

    /**
     * @param string $expectedMessage
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\AuthRestApi\Controller\TokenResourceController|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getTokenResourceControllerMock(
        string $expectedMessage,
        bool $isValidOauthResponse
    ): TokenResourceController {
        $tokenResourceControllerMock = $this->getMockBuilder(TokenResourceController::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $tokenResourceControllerMock->method('getFactory')->willReturn(
            $this->getAuthRestApiFactoryMock($expectedMessage, $isValidOauthResponse),
        );

        return $tokenResourceControllerMock;
    }

    /**
     * @param string $expectedMessage
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\AuthRestApi\AuthRestApiFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAuthRestApiFactoryMock(string $expectedMessage, bool $isValidOauthResponse): AuthRestApiFactory
    {
        $authRestApiFactoryMock = $this->createMock(AuthRestApiFactory::class);
        $authRestApiFactoryMock->method('createOauthToken')
            ->willReturn($this->getOauthTokenMock($expectedMessage, $isValidOauthResponse));

        return $authRestApiFactoryMock;
    }
}
