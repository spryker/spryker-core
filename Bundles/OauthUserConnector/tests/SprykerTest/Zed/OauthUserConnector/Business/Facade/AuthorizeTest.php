<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthUserConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AuthorizationRequestBuilder;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorDependencyProvider;
use Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeAuthorizationCheckerPluginInterface;
use SprykerTest\Zed\OauthUserConnector\OauthUserConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthUserConnector
 * @group Business
 * @group AuthorizeTest
 * Add your own group annotations below this line
 */
class AuthorizeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OauthUserConnector\OauthUserConnectorBusinessTester
     */
    protected OauthUserConnectorBusinessTester $tester;

    /**
     * @dataProvider getAuthorizationDataProvider
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param bool $scopeAuthorizationCheckerPluginResult
     * @param bool $expectedIsAuthorized
     *
     * @return void
     */
    public function testShouldHandleAuthorization(
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        bool $scopeAuthorizationCheckerPluginResult,
        bool $expectedIsAuthorized
    ): void {
        // Arrange
        $this->tester->setDependency(OauthUserConnectorDependencyProvider::PLUGINS_USER_TYPE_OAUTH_SCOPE_AUTHORIZATION_CHECKER, [
            $this->createUserTypeOauthScopeAuthorizationCheckerPluginMock($scopeAuthorizationCheckerPluginResult),
        ]);

        // Act
        $isAuthorized = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertSame($expectedIsAuthorized, $isAuthorized);
    }

    /**
     * @return array<string, array<int, \Generated\Shared\Transfer\AuthorizationRequestTransfer|bool>>
     */
    protected function getAuthorizationDataProvider(): array
    {
        return [
            'should authorize while glue request user is given' => [
                (new AuthorizationRequestBuilder([
                    AuthorizationRequestTransfer::ENTITY => [
                        'data' => [
                            'glueRequestUser' => new GlueRequestUserTransfer(),
                        ],
                    ],
                ]))->build(),
                true,
                true,
            ],
            'should not authorize while plugin ensured user has no oauth scope that authorizes an action' => [
                (new AuthorizationRequestBuilder([
                    AuthorizationRequestTransfer::ENTITY => [
                        'data' => [
                            'glueRequestUser' => new GlueRequestUserTransfer(),
                        ],
                    ],
                ]))->build(),
                false,
                false,
            ],
            'should authorize while glue request user is not given' => [
                (new AuthorizationRequestBuilder([
                    AuthorizationRequestTransfer::ENTITY => [],
                ]))->build(),
                false,
                true,
            ],
        ];
    }

    /**
     * @param bool $isAuthorized
     *
     * @return \Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeAuthorizationCheckerPluginInterface
     */
    protected function createUserTypeOauthScopeAuthorizationCheckerPluginMock(bool $isAuthorized): UserTypeOauthScopeAuthorizationCheckerPluginInterface
    {
        $userTypeOauthScopeAuthorizationCheckerPluginMock = $this->createMock(UserTypeOauthScopeAuthorizationCheckerPluginInterface::class);

        $userTypeOauthScopeAuthorizationCheckerPluginMock->method('authorize')->willReturn($isAuthorized);

        return $userTypeOauthScopeAuthorizationCheckerPluginMock;
    }
}
