<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthWarehouseUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuthorizationEntityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Spryker\Zed\OauthWarehouseUser\Business\OauthWarehouseUserBusinessFactory;
use Spryker\Zed\OauthWarehouseUser\Business\OauthWarehouseUserFacadeInterface;
use Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig;
use SprykerTest\Zed\OauthWarehouseUser\OauthWarehouseUserTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthWarehouseUser
 * @group Business
 * @group Facade
 * @group AuthorizeByWarehouseUserScopeTest
 * Add your own group annotations below this line
 */
class AuthorizeByWarehouseUserScopeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig::SCOPE_WAREHOUSE_USER
     *
     * @var string
     */
    protected const SCOPE_WAREHOUSE_USER = 'warehouse-user';

    /**
     * @uses \Spryker\Zed\OauthWarehouseUser\Business\Checker\WarehouseUserTypeOauthScopeAuthorizationChecker::GLUE_REQUEST_USER
     *
     * @var string
     */
    protected const GLUE_REQUEST_USER = 'glueRequestUser';

    /**
     * @uses \Spryker\Zed\OauthWarehouseUser\Business\Checker\WarehouseUserTypeOauthScopeAuthorizationChecker::METHOD
     *
     * @var string
     */
    protected const METHOD = 'method';

    /**
     * @uses \Spryker\Zed\OauthWarehouseUser\Business\Checker\WarehouseUserTypeOauthScopeAuthorizationChecker::PATH
     *
     * @var string
     */
    protected const PATH = 'path';

    /**
     * @var \SprykerTest\Zed\OauthWarehouseUser\OauthWarehouseUserTester
     */
    protected OauthWarehouseUserTester $tester;

    /**
     * @dataProvider getAuthorizeDataProvider
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param array<string, mixed> $allowedForWarehouseUserPaths
     * @param bool $expectedIsPathAllowed
     *
     * @return void
     */
    public function testShouldCorrectlyHandleAuthorization(
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        array $allowedForWarehouseUserPaths,
        bool $expectedIsPathAllowed
    ): void {
        // Arrange
        $oauthWarehouseUesrFacade = $this->getFacadeWithMockedConfig($allowedForWarehouseUserPaths);

        // Act
        $isPathAllowed = $oauthWarehouseUesrFacade->authorizeByWarehouseUserScope($authorizationRequestTransfer);

        // Assert
        $this->assertSame($expectedIsPathAllowed, $isPathAllowed);
    }

    /**
     * @return array<array<string, mixed>>
     */
    protected function getAuthorizeDataProvider(): array
    {
        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())->setEntity(new AuthorizationEntityTransfer());

        return [
            'should return false when request data has invalid user' => [
                (new AuthorizationRequestTransfer())->setEntity(
                    new AuthorizationEntityTransfer(),
                ),
                [],
                false,
            ],
            'should return false when request data method is missing' => [
                (new AuthorizationRequestTransfer())->setEntity(
                    (new AuthorizationEntityTransfer())->setData([
                        static::GLUE_REQUEST_USER => (new GlueRequestUserTransfer())->addScope(
                            static::SCOPE_WAREHOUSE_USER,
                        ),
                        static::PATH => '/example',
                    ]),
                ),
                [],
                false,
            ],
            'should return false when request data path is missing' => [
                (new AuthorizationRequestTransfer())->setEntity(
                    (new AuthorizationEntityTransfer())->setData([
                        static::GLUE_REQUEST_USER => (new GlueRequestUserTransfer())->addScope(
                            static::SCOPE_WAREHOUSE_USER,
                        ),
                        static::METHOD => 'GET',
                    ]),
                ),
                [],
                false,
            ],
            'should return false when user does not have warehouse user scope' => [
                (new AuthorizationRequestTransfer())->setEntity(
                    (new AuthorizationEntityTransfer())->setData([
                        static::GLUE_REQUEST_USER => new GlueRequestUserTransfer(),
                        static::METHOD => 'GET',
                        static::PATH => '/example',
                    ]),
                ),
                [],
                false,
            ],
            'should return false when no allowed paths specified' => [
                (new AuthorizationRequestTransfer())->setEntity(
                    (new AuthorizationEntityTransfer())->setData([
                        static::GLUE_REQUEST_USER => (new GlueRequestUserTransfer())->addScope(
                            static::SCOPE_WAREHOUSE_USER,
                        ),
                        static::METHOD => 'GET',
                        static::PATH => '/example',
                    ]),
                ),
                [],
                false,
            ],
            'should return true when allowed by path' => [
                (new AuthorizationRequestTransfer())->setEntity(
                    (new AuthorizationEntityTransfer())->setData([
                        static::GLUE_REQUEST_USER => (new GlueRequestUserTransfer())->addScope(
                            static::SCOPE_WAREHOUSE_USER,
                        ),
                        static::METHOD => 'GET',
                        static::PATH => '/example',
                    ]),
                ),
                [
                    '/example' => [
                        'isRegularExpression' => false,
                    ],
                ],
                true,
            ],
            'should return true when allowed by regular expression' => [
                (new AuthorizationRequestTransfer())->setEntity(
                    (new AuthorizationEntityTransfer())->setData([
                        static::GLUE_REQUEST_USER => (new GlueRequestUserTransfer())->addScope(
                            static::SCOPE_WAREHOUSE_USER,
                        ),
                        static::METHOD => 'GET',
                        static::PATH => '/example',
                    ]),
                ),
                [
                    '/\/example.*/' => [
                        'isRegularExpression' => true,
                        'methods' => [
                            'get',
                        ],
                    ],
                ],
                true,
            ],
            'should return false when not allowed by path or regular expression' => [
                (new AuthorizationRequestTransfer())->setEntity(
                    (new AuthorizationEntityTransfer())->setData([
                        static::GLUE_REQUEST_USER => (new GlueRequestUserTransfer())->addScope(
                            static::SCOPE_WAREHOUSE_USER,
                        ),
                        static::METHOD => 'GET',
                        static::PATH => '/example',
                    ]),
                ),
                [
                    '/\/other-example.*/' => [
                        'isRegularExpression' => true,
                        'methods' => [
                            'get',
                        ],
                    ],
                ],
                false,
            ],
            'should return false when method not allowed for route' => [
                (new AuthorizationRequestTransfer())->setEntity(
                    (new AuthorizationEntityTransfer())->setData([
                        static::GLUE_REQUEST_USER => (new GlueRequestUserTransfer())->addScope(
                            static::SCOPE_WAREHOUSE_USER,
                        ),
                        static::METHOD => 'POST',
                        static::PATH => '/example',
                    ]),
                ),
                [
                    '/\/example.*/' => [
                        'isRegularExpression' => true,
                        'methods' => [
                            'get',
                        ],
                    ],
                ],
                false,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $allowedForWarehouseUserPaths
     *
     * @return \Spryker\Zed\OauthWarehouseUser\Business\OauthWarehouseUserFacadeInterface
     */
    protected function getFacadeWithMockedConfig(array $allowedForWarehouseUserPaths): OauthWarehouseUserFacadeInterface
    {
        $oauthWarehouseUserConfigMock = $this->createOauthWarehouseUserConfigMock($allowedForWarehouseUserPaths);
        $oauthWarehouseUserBusinessFactoryMock = $this->createOauthWarehouseUserBusinessFactoryMock();

        $oauthWarehouseUserBusinessFactoryMock->setConfig($oauthWarehouseUserConfigMock);

        return $this->tester->getFacade()->setFactory($oauthWarehouseUserBusinessFactoryMock);
    }

    /**
     * @return \Spryker\Zed\OauthWarehouseUser\Business\OauthWarehouseUserBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOauthWarehouseUserBusinessFactoryMock(): OauthWarehouseUserBusinessFactory
    {
        return $this->getMockBuilder(OauthWarehouseUserBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @param array<string, mixed> $allowedForWarehouseUserPaths
     *
     * @return \Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOauthWarehouseUserConfigMock(array $allowedForWarehouseUserPaths): OauthWarehouseUserConfig
    {
        $oauthWarehouseUserConfigMock = $this->createMock(OauthWarehouseUserConfig::class);

        $oauthWarehouseUserConfigMock->method('getAllowedForWarehouseUserPaths')->willReturn($allowedForWarehouseUserPaths);
        $oauthWarehouseUserConfigMock->method('getWarehouseUserScope')->willReturn(static::SCOPE_WAREHOUSE_USER);

        return $oauthWarehouseUserConfigMock;
    }
}
