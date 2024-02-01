<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthMerchantUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuthorizationEntityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Spryker\Zed\OauthMerchantUser\Business\OauthMerchantUserBusinessFactory;
use Spryker\Zed\OauthMerchantUser\Business\OauthMerchantUserFacadeInterface;
use Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig;
use SprykerTest\Zed\OauthMerchantUser\OauthMerchantUserTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthMerchantUser
 * @group Business
 * @group Facade
 * @group AuthorizeByMerchantUserScopeTest
 * Add your own group annotations below this line
 */
class AuthorizeByMerchantUserScopeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig::SCOPE_MERCHANT_USER
     *
     * @var string
     */
    protected const SCOPE_MERCHANT_USER = 'merchant-user';

    /**
     * @uses \Spryker\Zed\OauthMerchantUser\Business\Checker\MerchantUserTypeOauthScopeAuthorizationChecker::GLUE_REQUEST_USER
     *
     * @var string
     */
    protected const GLUE_REQUEST_USER = 'glueRequestUser';

    /**
     * @uses \Spryker\Zed\OauthMerchantUser\Business\Checker\MerchantUserTypeOauthScopeAuthorizationChecker::METHOD
     *
     * @var string
     */
    protected const METHOD = 'method';

    /**
     * @uses \Spryker\Zed\OauthMerchantUser\Business\Checker\MerchantUserTypeOauthScopeAuthorizationChecker::PATH
     *
     * @var string
     */
    protected const PATH = 'path';

    /**
     * @var \SprykerTest\Zed\OauthMerchantUser\OauthMerchantUserTester
     */
    protected OauthMerchantUserTester $tester;

    /**
     * @dataProvider getAuthorizeDataProvider
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param array<string, mixed> $allowedForMerchantUserPaths
     * @param bool $expectedIsPathAllowed
     *
     * @return void
     */
    public function testShouldCorrectlyHandleAuthorization(
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        array $allowedForMerchantUserPaths,
        bool $expectedIsPathAllowed
    ): void {
        // Arrange
        $oauthWarehouseUesrFacade = $this->getFacadeWithMockedConfig($allowedForMerchantUserPaths);

        // Act
        $isPathAllowed = $oauthWarehouseUesrFacade->authorizeByMerchantUserScope($authorizationRequestTransfer);

        // Assert
        $this->assertSame($expectedIsPathAllowed, $isPathAllowed);
    }

    /**
     * @return array<array>
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
                            static::SCOPE_MERCHANT_USER,
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
                            static::SCOPE_MERCHANT_USER,
                        ),
                        static::METHOD => 'GET',
                    ]),
                ),
                [],
                false,
            ],
            'should return false when user does not have merchant user scope' => [
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
                            static::SCOPE_MERCHANT_USER,
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
                            static::SCOPE_MERCHANT_USER,
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
                            static::SCOPE_MERCHANT_USER,
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
                            static::SCOPE_MERCHANT_USER,
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
                            static::SCOPE_MERCHANT_USER,
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
     * @param array<string, mixed> $allowedForMerchantUserPaths
     *
     * @return \Spryker\Zed\OauthMerchantUser\Business\OauthMerchantUserFacadeInterface
     */
    protected function getFacadeWithMockedConfig(array $allowedForMerchantUserPaths): OauthMerchantUserFacadeInterface
    {
        $oauthMerchantUserConfigMock = $this->createOauthMerchantUserConfigMock($allowedForMerchantUserPaths);
        $oauthMerchantUserBusinessFactoryMock = $this->createOauthMerchantUserBusinessFactoryMock();

        $oauthMerchantUserBusinessFactoryMock->setConfig($oauthMerchantUserConfigMock);

        return $this->tester->getFacade()->setFactory($oauthMerchantUserBusinessFactoryMock);
    }

    /**
     * @return \Spryker\Zed\OauthMerchantUser\Business\OauthMerchantUserBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOauthMerchantUserBusinessFactoryMock(): OauthMerchantUserBusinessFactory
    {
        return $this->getMockBuilder(OauthMerchantUserBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @param array<string, mixed> $allowedForMerchantUserPaths
     *
     * @return \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOauthMerchantUserConfigMock(array $allowedForMerchantUserPaths): OauthMerchantUserConfig
    {
        $oauthMerchantUserConfigMock = $this->createMock(OauthMerchantUserConfig::class);

        $oauthMerchantUserConfigMock->method('getAllowedForMerchantUserPaths')->willReturn($allowedForMerchantUserPaths);
        $oauthMerchantUserConfigMock->method('getMerchantUserScope')->willReturn(static::SCOPE_MERCHANT_USER);

        return $oauthMerchantUserConfigMock;
    }
}
