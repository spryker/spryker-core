<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthClient\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AccessTokenErrorTransfer;
use Generated\Shared\Transfer\AccessTokenRequestOptionsTransfer;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCacheQuery;
use Spryker\Zed\OauthClient\Business\Exception\AccessTokenProviderNotFoundException;
use Spryker\Zed\OauthClient\Business\Provider\OauthAccessTokenProviderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthClient
 * @group Business
 * @group Facade
 * @group OauthClientFacadeTest
 * Add your own group annotations below this line
 */
class OauthClientFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OauthClient\OauthClientBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const TEST_SUCCESS_PROVIDER_NAME = 'test-success-provider-name';

    /**
     * @var string
     */
    protected const TEST_ERROR_PROVIDER_NAME = 'test-error-provider-name';

    /**
     * @var string
     */
    protected const TEST_UNSUPPORTED_PROVIDER_NAME = 'test-unsupported-provider-name';

    /**
     * @var string
     */
    protected const TEST_TOKEN_FROM_PROVIDER = 'test-token-from-provider';

    /**
     * @var string
     */
    protected const TEST_TOKEN_FROM_CACHE = 'test-token-from-cache';

    /**
     * @var int
     */
    protected const TEST_EXPIRES_IN = 86400;

    /**
     * @var string
     */
    protected const TEST_ERROR = 'TEST_ERROR';

    /**
     * @var \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    protected $expectedSuccessAccessTokenResponseTransferFromProvider;

    /**
     * @var \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    protected $expectedErrorAccessTokenResponseTransferFromProvider;

    /**
     * @var string
     */
    protected $expiresAt;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->expiresAt = (string)(time() + static::TEST_EXPIRES_IN);

        $this->expectedSuccessAccessTokenResponseTransferFromProvider = $this->tester->haveAccessTokenResponseTransfer([
            AccessTokenResponseTransfer::IS_SUCCESSFUL => true,
            AccessTokenResponseTransfer::ACCESS_TOKEN => static::TEST_TOKEN_FROM_PROVIDER,
            AccessTokenResponseTransfer::EXPIRES_AT => $this->expiresAt,
        ]);

        $this->expectedErrorAccessTokenResponseTransferFromProvider = $this->tester->haveAccessTokenResponseTransfer([
            AccessTokenResponseTransfer::IS_SUCCESSFUL => false,
            AccessTokenResponseTransfer::ACCESS_TOKEN_ERROR => [
                AccessTokenErrorTransfer::ERROR => static::TEST_ERROR,
            ],
        ]);

        $this->tester->setOauthAccessTokenProviderPluginsDependency([
            $this->tester->mockOauthAccessTokenProviderPlugin(
                static::TEST_SUCCESS_PROVIDER_NAME,
                $this->expectedSuccessAccessTokenResponseTransferFromProvider,
            ),
            $this->tester->mockOauthAccessTokenProviderPlugin(
                static::TEST_ERROR_PROVIDER_NAME,
                $this->expectedErrorAccessTokenResponseTransferFromProvider,
            ),
        ]);
    }

    /**
     * @return void
     */
    public function testGetNotCachedAccessTokenReturnsValidToken(): void
    {
        // Arrange
        $accessTokenRequestTransfer = $this->tester->haveAccessTokenRequestTransfer([
            AccessTokenRequestTransfer::PROVIDER_NAME => static::TEST_SUCCESS_PROVIDER_NAME,
            AccessTokenRequestTransfer::ACCESS_TOKEN_REQUEST_OPTIONS => [
                AccessTokenRequestOptionsTransfer::STORE_REFERENCE => __FUNCTION__,
            ],
        ]);

        $spyOauthClientAccessTokenCacheEntityBeforeRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        // Act
        $accessTokenResponseTransfer = $this->tester->getFacade()->getAccessToken($accessTokenRequestTransfer);

        // Assert
        $spyOauthClientAccessTokenCacheEntityAfterRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));
        $this->tester->removeCacheEntity($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        $this->assertInstanceOf(AccessTokenResponseTransfer::class, $accessTokenResponseTransfer);
        $this->assertTrue($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertEquals($this->expectedSuccessAccessTokenResponseTransferFromProvider, $accessTokenResponseTransfer);
        $this->assertNull($spyOauthClientAccessTokenCacheEntityBeforeRequest);
        $this->assertNotNull($spyOauthClientAccessTokenCacheEntityAfterRequest);
    }

    /**
     * @return void
     */
    public function testGetCachedAccessTokenReturnsValidToken(): void
    {
        // Arrange
        $accessTokenRequestTransfer = $this->tester->haveAccessTokenRequestTransfer([
            AccessTokenRequestTransfer::PROVIDER_NAME => static::TEST_SUCCESS_PROVIDER_NAME,
            AccessTokenRequestTransfer::ACCESS_TOKEN_REQUEST_OPTIONS => [
                AccessTokenRequestOptionsTransfer::STORE_REFERENCE => __FUNCTION__,
            ],
        ]);

        $expectedAccessTokenResponseTransferFromCache = $this->tester->haveAccessTokenResponseTransfer([
            AccessTokenResponseTransfer::ACCESS_TOKEN => static::TEST_TOKEN_FROM_CACHE,
            AccessTokenResponseTransfer::EXPIRES_AT => $this->expiresAt,
            AccessTokenResponseTransfer::IS_SUCCESSFUL => true,
        ]);

        $this->tester->haveOauthClientAccessTokenCacheEntity(
            $accessTokenRequestTransfer,
            $expectedAccessTokenResponseTransferFromCache,
        );

        $spyOauthClientAccessTokenCacheEntityBeforeRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        // Act
        $accessTokenResponseTransfer = $this->tester->getFacade()->getAccessToken($accessTokenRequestTransfer);

        // Assert
        $spyOauthClientAccessTokenCacheEntityAfterRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        $this->assertInstanceOf(AccessTokenResponseTransfer::class, $accessTokenResponseTransfer);
        $this->assertTrue($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertEquals($expectedAccessTokenResponseTransferFromCache, $accessTokenResponseTransfer);
        $this->assertNotNull($spyOauthClientAccessTokenCacheEntityBeforeRequest);
        $this->assertNotNull($spyOauthClientAccessTokenCacheEntityAfterRequest);
    }

    /**
     * @return void
     */
    public function testGetCachedButExpiredAccessTokenReturnsValidTokenAndUpdateCache(): void
    {
        // Arrange
        $accessTokenRequestTransfer = $this->tester->haveAccessTokenRequestTransfer([
            AccessTokenRequestTransfer::PROVIDER_NAME => static::TEST_SUCCESS_PROVIDER_NAME,
            AccessTokenRequestTransfer::ACCESS_TOKEN_REQUEST_OPTIONS => [
                AccessTokenRequestOptionsTransfer::STORE_REFERENCE => __FUNCTION__,
            ],
        ]);

        $expectedAccessTokenResponseTransferFromCache = $this->tester->haveAccessTokenResponseTransfer([
            AccessTokenResponseTransfer::ACCESS_TOKEN => static::TEST_TOKEN_FROM_CACHE,
            AccessTokenResponseTransfer::EXPIRES_AT => (string)(time() - 1000),
        ]);

        $this->tester->haveOauthClientAccessTokenCacheEntity(
            $accessTokenRequestTransfer,
            $expectedAccessTokenResponseTransferFromCache,
        );

        $spyOauthClientAccessTokenCacheEntityBeforeRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        // Act
        $accessTokenResponseTransfer = $this->tester->getFacade()->getAccessToken($accessTokenRequestTransfer);

        // Assert
        $spyOauthClientAccessTokenCacheEntityAfterRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));
        $this->tester->removeCacheEntity($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        $this->assertInstanceOf(AccessTokenResponseTransfer::class, $accessTokenResponseTransfer);
        $this->assertTrue($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertEquals($this->expectedSuccessAccessTokenResponseTransferFromProvider, $accessTokenResponseTransfer);
        $this->assertNotNull($spyOauthClientAccessTokenCacheEntityBeforeRequest);
        $this->assertNotNull($spyOauthClientAccessTokenCacheEntityAfterRequest);
        $this->assertNotEquals(
            $spyOauthClientAccessTokenCacheEntityBeforeRequest->getIdSpyOauthClientAccessTokenCache(),
            $spyOauthClientAccessTokenCacheEntityAfterRequest->getIdSpyOauthClientAccessTokenCache(),
        );
    }

    /**
     * @return void
     */
    public function testGetAccessTokenButCacheDisabledReturnsValidToken(): void
    {
        // Arrange
        $accessTokenRequestTransfer = $this->tester->haveAccessTokenRequestTransfer([
            AccessTokenRequestTransfer::IGNORE_CACHE => true,
            AccessTokenRequestTransfer::PROVIDER_NAME => static::TEST_SUCCESS_PROVIDER_NAME,
            AccessTokenRequestTransfer::ACCESS_TOKEN_REQUEST_OPTIONS => [
                AccessTokenRequestOptionsTransfer::STORE_REFERENCE => __FUNCTION__,
            ],
        ]);

        $spyOauthClientAccessTokenCacheEntityBeforeRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        // Act
        $accessTokenResponseTransfer = $this->tester->getFacade()->getAccessToken($accessTokenRequestTransfer);

        // Assert
        $spyOauthClientAccessTokenCacheEntityAfterRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        $this->assertInstanceOf(AccessTokenResponseTransfer::class, $accessTokenResponseTransfer);
        $this->assertTrue($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertEquals($this->expectedSuccessAccessTokenResponseTransferFromProvider, $accessTokenResponseTransfer);
        $this->assertNull($spyOauthClientAccessTokenCacheEntityBeforeRequest);
        $this->assertNull($spyOauthClientAccessTokenCacheEntityAfterRequest);
    }

    /**
     * @return void
     */
    public function testGetCachedAccessTokenButCacheDisabledReturnsValidToken(): void
    {
        // Arrange
        $accessTokenRequestTransfer = $this->tester->haveAccessTokenRequestTransfer([
            AccessTokenRequestTransfer::IGNORE_CACHE => true,
            AccessTokenRequestTransfer::PROVIDER_NAME => static::TEST_SUCCESS_PROVIDER_NAME,
            AccessTokenRequestTransfer::ACCESS_TOKEN_REQUEST_OPTIONS => [
                AccessTokenRequestOptionsTransfer::STORE_REFERENCE => __FUNCTION__,
            ],
        ]);

        $expectedAccessTokenResponseTransferFromCache = $this->tester->haveAccessTokenResponseTransfer([
            AccessTokenResponseTransfer::ACCESS_TOKEN => static::TEST_TOKEN_FROM_CACHE,
            AccessTokenResponseTransfer::EXPIRES_AT => $this->expiresAt,
        ]);

        $this->tester->haveOauthClientAccessTokenCacheEntity(
            $accessTokenRequestTransfer,
            $expectedAccessTokenResponseTransferFromCache,
        );

        $spyOauthClientAccessTokenCacheEntityBeforeRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        // Act
        $accessTokenResponseTransfer = $this->tester->getFacade()->getAccessToken($accessTokenRequestTransfer);

        // Assert
        $spyOauthClientAccessTokenCacheEntityAfterRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        $this->assertInstanceOf(AccessTokenResponseTransfer::class, $accessTokenResponseTransfer);
        $this->assertTrue($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertEquals($this->expectedSuccessAccessTokenResponseTransferFromProvider, $accessTokenResponseTransfer);
        $this->assertNotNull($spyOauthClientAccessTokenCacheEntityBeforeRequest);
        $this->assertNotNull($spyOauthClientAccessTokenCacheEntityAfterRequest);
        $this->assertEquals(
            $spyOauthClientAccessTokenCacheEntityBeforeRequest->getIdSpyOauthClientAccessTokenCache(),
            $spyOauthClientAccessTokenCacheEntityAfterRequest->getIdSpyOauthClientAccessTokenCache(),
        );
    }

    /**
     * @return void
     */
    public function testGetAccessTokenReturnsErrorWhenPluginReturnsError(): void
    {
        // Arrange
        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setProviderName(static::TEST_ERROR_PROVIDER_NAME);

        // Act
        $accessTokenResponseTransfer = $this->tester->getFacade()->getAccessToken($accessTokenRequestTransfer);

        // Assert
        $spyOauthClientAccessTokenCacheEntityAfterRequest = SpyOauthClientAccessTokenCacheQuery::create()
            ->findOneByCacheKey($this->tester->hashAccessTokenRequestTransfer($accessTokenRequestTransfer));

        $this->assertInstanceOf(AccessTokenResponseTransfer::class, $accessTokenResponseTransfer);
        $this->assertFalse($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertNull($spyOauthClientAccessTokenCacheEntityAfterRequest);
        $this->assertEquals(
            $this->expectedErrorAccessTokenResponseTransferFromProvider,
            $accessTokenResponseTransfer,
        );
    }

    /**
     * @return void
     */
    public function testGetAccessTokenReturnsErrorWhenPluginNotFound(): void
    {
        // Arrange
        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setProviderName(static::TEST_UNSUPPORTED_PROVIDER_NAME);

        // Assert
        $this->expectException(AccessTokenProviderNotFoundException::class);

        // Act
        $this->tester->getFacade()->getAccessToken($accessTokenRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAccessTokenRequestOptionsExpandedWithStoreReferenceTakenFromMessageAttributes(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('isAccessTokenRequestExpandedByMessageAttributes', true);

        $mesageAttributes = $this->tester->haveMessageAttributes(
            [
                'storeReference' => 'store-reference-1',
            ],
        );

        $oauthAccessTokenProviderMock = $this->makeEmpty(OauthAccessTokenProviderInterface::class);
        $oauthAccessTokenProviderMock->expects($this->once())
            ->method('getAccessToken')
            ->with(static::callback(function (AccessTokenRequestTransfer $accessTokenRequestTransfer): bool {
                // Assert
                self::assertSame('store-reference-1', $accessTokenRequestTransfer->getAccessTokenRequestOptions()->getStoreReference());

                return true;
            }))
            ->willReturn(
                $this->tester->haveAccessTokenResponseTransfer(
                    [
                        AccessTokenResponseTransfer::IS_SUCCESSFUL => true,
                        AccessTokenResponseTransfer::ACCESS_TOKEN => static::TEST_TOKEN_FROM_PROVIDER,
                        AccessTokenResponseTransfer::EXPIRES_AT => $this->expiresAt,
                    ],
                ),
            );

        $this->tester->mockFactoryMethod('createOauthAccessTokenProvider', $oauthAccessTokenProviderMock);

        // Act
        $expandedMessageAttributes = $this->tester->getFacade()->expandMessageAttributes($mesageAttributes);
    }

    /**
     * @return void
     */
    public function testAccessTokenRequestOptionsExpandedWithCurrentStoreStoreReference(): void
    {
        // Arrange
        $mesageAttributes = $this->tester->haveMessageAttributes(
            [
                'storeReference' => 'store-reference-1',
            ],
        );

        $oauthAccessTokenProviderMock = $this->makeEmpty(OauthAccessTokenProviderInterface::class);
        $oauthAccessTokenProviderMock->expects($this->once())
            ->method('getAccessToken')
            ->with(static::callback(function (AccessTokenRequestTransfer $accessTokenRequestTransfer): bool {
                // Assert
                self::assertNull($accessTokenRequestTransfer->getAccessTokenRequestOptions()->getStoreReference());

                return true;
            }))
            ->willReturn(
                $this->tester->haveAccessTokenResponseTransfer(
                    [
                        AccessTokenResponseTransfer::IS_SUCCESSFUL => true,
                        AccessTokenResponseTransfer::ACCESS_TOKEN => static::TEST_TOKEN_FROM_PROVIDER,
                        AccessTokenResponseTransfer::EXPIRES_AT => $this->expiresAt,
                    ],
                ),
            );

        $this->tester->mockFactoryMethod('createOauthAccessTokenProvider', $oauthAccessTokenProviderMock);

        // Act
        $expandedMessageAttributes = $this->tester->getFacade()->expandMessageAttributes($mesageAttributes);
    }
}
