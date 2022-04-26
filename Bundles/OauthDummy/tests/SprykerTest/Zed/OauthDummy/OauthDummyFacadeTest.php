<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthDummy\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AccessTokenRequestOptionsTransfer;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthDummy
 * @group Business
 * @group Facade
 * @group OauthDummyFacadeTest
 * Add your own group annotations below this line
 */
class OauthDummyFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_AUDIENCE = 'test-audience';

    /**
     * @var string
     */
    protected const TEST_STORE_REFERENCE = 'test-store-reference';

    /**
     * @var \SprykerTest\Zed\OauthDummy\OauthDummyBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateAccessTokenSuccess(): void
    {
        // Arrange
        $accessTokenRequestOptionsTransfer = (new AccessTokenRequestOptionsTransfer())
            ->setAudience(static::TEST_AUDIENCE)
            ->setStoreReference(static::TEST_STORE_REFERENCE);

        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        // Act
        $accessTokenResponseTransfer = $this->tester->getFacade()->generateAccessToken($accessTokenRequestTransfer);

        // Assert
        $decodedToken = $this->tester->decodeAccessToken($accessTokenResponseTransfer->getAccessToken());
        $this->assertGreaterThan(time(), $accessTokenResponseTransfer->getExpiresAt());
        $this->assertEquals(static::TEST_AUDIENCE, $decodedToken['aud']);
        $this->assertEquals(static::TEST_STORE_REFERENCE, $decodedToken['store_reference']);
        $this->assertInstanceOf(AccessTokenResponseTransfer::class, $accessTokenResponseTransfer);
        $this->assertTrue($accessTokenResponseTransfer->getIsSuccessful());
    }
}
