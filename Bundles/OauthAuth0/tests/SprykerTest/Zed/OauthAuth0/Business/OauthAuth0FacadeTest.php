<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthAuth0\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Shared\OauthAuth0\OauthAuth0Constants;
use Spryker\Zed\OauthAuth0\OauthAuth0Config;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthAuth0
 * @group Business
 * @group Facade
 * @group OauthAuth0FacadeTest
 * Add your own group annotations below this line
 */
class OauthAuth0FacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const CLIENT_ID = 'test-token';

    /**
     * @var string
     */
    protected const CLIENT_SECRET = 'client-secret';

    /**
     * @var \SprykerTest\Zed\OauthAuth0\OauthAuth0BusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAccessTokenRequestContainsNullCacheKeySeedIfProviderNameIsIncorrect(): void
    {
        // Arrange
        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setProviderName('test-provider-name');

        // Act
        $this->tester->getFacade()->expandAccessTokenRequest($accessTokenRequestTransfer);

        // Assert
        $this->assertNull($accessTokenRequestTransfer->getCacheKeySeed());
    }

    /**
     * @return void
     */
    public function testAccessTokenRequestContainsCacheKeySeedIfProviderNameIsCorrect(): void
    {
        // Arrange
        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setProviderName(OauthAuth0Config::PROVIDER_NAME);
        $this->tester->setConfig(OauthAuth0Constants::AUTH0_CLIENT_ID, static::CLIENT_ID);
        $this->tester->setConfig(OauthAuth0Constants::AUTH0_CLIENT_SECRET, static::CLIENT_SECRET);

        // Act
        $this->tester->getFacade()->expandAccessTokenRequest($accessTokenRequestTransfer);

        // Assert
        $this->assertNotNull($accessTokenRequestTransfer->getProviderName());
        $this->assertNotNull($accessTokenRequestTransfer->getCacheKeySeed());
        $this->assertSame($accessTokenRequestTransfer->getCacheKeySeed(), $this->getCredentialsHash());
    }

    /**
     * @return string
     */
    protected function getCredentialsHash(): string
    {
        return sha1(static::CLIENT_ID . static::CLIENT_SECRET);
    }
}
