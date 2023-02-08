<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\OauthCustomerValidation;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\OauthErrorTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group OauthCustomerValidation
 * @group OauthCustomerValidationClientTest
 * Add your own group annotations below this line
 */
class OauthCustomerValidationClientTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_TOKEN = 'fake_token';

    /**
     * @var \SprykerTest\Client\OauthCustomerValidation\OauthCustomerValidationClientTester
     */
    protected OauthCustomerValidationClientTester $tester;

    /**
     * @return void
     */
    public function testValidateCustomerWithoutInvalidatedCustomerData(): void
    {
        // Arrange
        $this->tester->setOauthServiceMock(
            $this->tester->getOauthAccessTokenDataTransfer(true),
        );
        $this->tester->setCustomerStorageClientMock(new InvalidatedCustomerCollectionTransfer());

        // Act
        $oauthAccessTokenValidationResponseTransfer = $this->tester->getOauthCustomerValidationClient()->validateInvalidatedCustomerAccessToken(
            (new OauthAccessTokenValidationRequestTransfer())->setAccessToken(static::FAKE_TOKEN),
            new OauthAccessTokenValidationResponseTransfer(),
        );

        // Assert
        $this->assertInstanceOf(OauthAccessTokenValidationResponseTransfer::class, $oauthAccessTokenValidationResponseTransfer);
        $this->assertNull($oauthAccessTokenValidationResponseTransfer->getError());
    }

    /**
     * @return void
     */
    public function testValidateCustomerWithoutError(): void
    {
        // Arrange
        $this->tester->setOauthServiceMock(
            $this->tester->getOauthAccessTokenDataTransfer(true),
        );
        $this->tester->setCustomerStorageClientMock(
            $this->tester->getInvalidatedCustomerCollectionTransfer(null, null),
        );

        // Act
        $oauthAccessTokenValidationResponseTransfer = $this->tester->getOauthCustomerValidationClient()->validateInvalidatedCustomerAccessToken(
            (new OauthAccessTokenValidationRequestTransfer())->setAccessToken(static::FAKE_TOKEN),
            new OauthAccessTokenValidationResponseTransfer(),
        );

        // Assert
        $this->assertInstanceOf(OauthAccessTokenValidationResponseTransfer::class, $oauthAccessTokenValidationResponseTransfer);
        $this->assertNull($oauthAccessTokenValidationResponseTransfer->getError());
    }

    /**
     * @return void
     */
    public function testValidateCustomerWithAnonymizedAt(): void
    {
        // Arrange
        $this->tester->setOauthServiceMock(
            $this->tester->getOauthAccessTokenDataTransfer(true),
        );
        $this->tester->setCustomerStorageClientMock(
            $this->tester->getInvalidatedCustomerCollectionTransfer(new DateTime(), null),
        );

        // Act
        $oauthAccessTokenValidationResponseTransfer = $this->tester->getOauthCustomerValidationClient()->validateInvalidatedCustomerAccessToken(
            (new OauthAccessTokenValidationRequestTransfer())->setAccessToken(static::FAKE_TOKEN),
            new OauthAccessTokenValidationResponseTransfer(),
        );

        // Assert
        $this->assertInstanceOf(OauthAccessTokenValidationResponseTransfer::class, $oauthAccessTokenValidationResponseTransfer);
        $this->assertInstanceOf(OauthErrorTransfer::class, $oauthAccessTokenValidationResponseTransfer->getError());
    }

    /**
     * @return void
     */
    public function testValidateUserWithAnonymizedAt(): void
    {
        // Arrange
        $this->tester->setOauthServiceMock(
            $this->tester->getOauthAccessTokenDataTransfer(false),
        );
        $this->tester->setCustomerStorageClientMock(
            $this->tester->getInvalidatedCustomerCollectionTransfer(new DateTime(), null),
        );

        // Act
        $oauthAccessTokenValidationResponseTransfer = $this->tester->getOauthCustomerValidationClient()->validateInvalidatedCustomerAccessToken(
            (new OauthAccessTokenValidationRequestTransfer())->setAccessToken(static::FAKE_TOKEN),
            new OauthAccessTokenValidationResponseTransfer(),
        );

        // Assert
        $this->assertInstanceOf(OauthAccessTokenValidationResponseTransfer::class, $oauthAccessTokenValidationResponseTransfer);
        $this->assertNull($oauthAccessTokenValidationResponseTransfer->getError());
    }

    /**
     * @return void
     */
    public function testValidateCustomerWithPasswordUpdatedAtBeforeLogin(): void
    {
        // Arrange
        $this->tester->setOauthServiceMock(
            $this->tester->getOauthAccessTokenDataTransfer(true),
        );
        $this->tester->setCustomerStorageClientMock(
            $this->tester->getInvalidatedCustomerCollectionTransfer(null, new DateTime('-2 minutes')),
        );

        // Act
        $oauthAccessTokenValidationResponseTransfer = $this->tester->getOauthCustomerValidationClient()->validateInvalidatedCustomerAccessToken(
            (new OauthAccessTokenValidationRequestTransfer())->setAccessToken(static::FAKE_TOKEN),
            new OauthAccessTokenValidationResponseTransfer(),
        );

        // Assert
        $this->assertInstanceOf(OauthAccessTokenValidationResponseTransfer::class, $oauthAccessTokenValidationResponseTransfer);
        $this->assertNull($oauthAccessTokenValidationResponseTransfer->getError());
    }

    /**
     * @return void
     */
    public function testValidateCustomerWithPasswordUpdatedAtAfterLogin(): void
    {
        // Arrange
        $this->tester->setOauthServiceMock(
            $this->tester->getOauthAccessTokenDataTransfer(true),
        );
        $this->tester->setCustomerStorageClientMock(
            $this->tester->getInvalidatedCustomerCollectionTransfer(null, new DateTime()),
        );

        // Act
        $oauthAccessTokenValidationResponseTransfer = $this->tester->getOauthCustomerValidationClient()->validateInvalidatedCustomerAccessToken(
            (new OauthAccessTokenValidationRequestTransfer())->setAccessToken(static::FAKE_TOKEN),
            new OauthAccessTokenValidationResponseTransfer(),
        );

        // Assert
        $this->assertInstanceOf(OauthAccessTokenValidationResponseTransfer::class, $oauthAccessTokenValidationResponseTransfer);
        $this->assertInstanceOf(OauthErrorTransfer::class, $oauthAccessTokenValidationResponseTransfer->getError());
    }
}
