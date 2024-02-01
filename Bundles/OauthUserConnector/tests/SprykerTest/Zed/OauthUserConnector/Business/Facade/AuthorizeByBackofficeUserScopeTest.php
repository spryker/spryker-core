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
use SprykerTest\Zed\OauthUserConnector\OauthUserConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthUserConnector
 * @group Business
 * @group AuthorizeByBackofficeUserScopeTest
 * Add your own group annotations below this line
 */
class AuthorizeByBackofficeUserScopeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig::SCOPE_BACK_OFFICE_USER
     *
     * @var string
     */
    protected const SCOPE_BACK_OFFICE_USER = 'back-office-user';

    /**
     * @uses \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig::SCOPE_USER
     *
     * @var string
     */
    protected const SCOPE_USER = 'user';

    /**
     * @var \SprykerTest\Zed\OauthUserConnector\OauthUserConnectorBusinessTester
     */
    protected OauthUserConnectorBusinessTester $tester;

    /**
     * @dataProvider getAuthorizationDataProvider
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param bool $expectedIsAuthorized
     *
     * @return void
     */
    public function testShouldHandleAuthorizationRequest(
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        bool $expectedIsAuthorized
    ): void {
        // Act
        $isAuthorized = $this->tester->getFacade()->authorizeByBackofficeUserScope($authorizationRequestTransfer);

        // Assert
        $this->assertSame($expectedIsAuthorized, $isAuthorized);
    }

    /**
     * @return array<string<array<int, \Generated\Shared\Transfer\AuthorizationRequestTransfer|bool>>>
     */
    protected function getAuthorizationDataProvider(): array
    {
        return [
            'should not authorize while glue request user is missing' => [
                (new AuthorizationRequestBuilder([
                    AuthorizationRequestTransfer::ENTITY => [
                        'data' => [],
                    ],
                ]))->build(),
                false,
            ],
            'should authorize while glue request user has back office user scope' => [
                (new AuthorizationRequestBuilder([
                    AuthorizationRequestTransfer::ENTITY => [
                        'data' => [
                            'glueRequestUser' => (new GlueRequestUserTransfer())->addScope(static::SCOPE_BACK_OFFICE_USER),
                        ],
                    ],
                ]))->build(),
                true,
            ],
            'should authorize while glue request user has required user scopes' => [
                (new AuthorizationRequestBuilder([
                    AuthorizationRequestTransfer::ENTITY => [
                        'data' => [
                            'glueRequestUser' => (new GlueRequestUserTransfer())->addScope(static::SCOPE_USER),
                        ],
                    ],
                ]))->build(),
                true,
            ],
            'should not authorize while glue request user has not required scopes' => [
                (new AuthorizationRequestBuilder([
                    AuthorizationRequestTransfer::ENTITY => [
                        'data' => [
                            'glueRequestUser' => (new GlueRequestUserTransfer()),
                        ],
                    ],
                ]))->build(),
                false,
            ],
        ];
    }
}
