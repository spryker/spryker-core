<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AuthRestApi\Controller;

use Generated\Shared\Transfer\RestAccessTokensAttributesTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiFactory;
use Spryker\Glue\AuthRestApi\Controller\AccessTokensResourceController;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group AuthRestApi
 * @group Controller
 * @group AccessTokensResourceControllerTest
 * Add your own group annotations below this line
 */
class AccessTokensResourceControllerTest extends AbstractControllerTest
{
    /**
     * @return void
     */
    public function testPostActionAddsFailedLoginAuditLogWhenLoginAttemptFails(): void
    {
        // Arrange
        $restRequest = (new RestRequest())->createRestRequest();
        $restAccessTokensAttributesTransfer = new RestAccessTokensAttributesTransfer();
        $accessTokensResourceControllerMock = $this->getAccessTokensResourceControllerMock('Failed Login', false);

        // Act
        $accessTokensResourceControllerMock->postAction($restRequest, $restAccessTokensAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testPostActionAddsSuccessfulLoginAuditLogOnSuccessfulLogin(): void
    {
        // Arrange
        $restRequest = (new RestRequest())->createRestRequest();
        $restAccessTokensAttributesTransfer = new RestAccessTokensAttributesTransfer();
        $accessTokensResourceControllerMock = $this->getAccessTokensResourceControllerMock('Successful Login', true);

        // Act
        $accessTokensResourceControllerMock->postAction($restRequest, $restAccessTokensAttributesTransfer);
    }

    /**
     * @param string $expectedMessage
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\AuthRestApi\Controller\AccessTokensResourceController|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAccessTokensResourceControllerMock(
        string $expectedMessage,
        bool $isValidOauthResponse
    ): AccessTokensResourceController {
        $accessTokensResourceControllerMock = $this->getMockBuilder(AccessTokensResourceController::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $accessTokensResourceControllerMock->method('getFactory')->willReturn(
            $this->getAuthRestApiFactoryMock($expectedMessage, $isValidOauthResponse),
        );

        return $accessTokensResourceControllerMock;
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
        $authRestApiFactoryMock->method('createAccessTokensReader')
            ->willReturn($this->getAccessTokensReaderMock($expectedMessage, $isValidOauthResponse));

        return $authRestApiFactoryMock;
    }
}
