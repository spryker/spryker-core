<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\MerchantApp\MerchantAppTests\AppEvents;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Ramsey\Uuid\Uuid;
use SprykerTest\AsyncApi\MerchantApp\MerchantAppAsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group MerchantApp
 * @group MerchantAppTests
 * @group AppEvents
 * @group AppConfigUpdatedTest
 * Add your own group annotations below this line
 */
class AppConfigUpdatedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\MerchantApp\MerchantAppAsyncApiTester
     */
    protected MerchantAppAsyncApiTester $tester;

    /**
     * @return void
     */
    public function testMerchantAppOnboardingDataIsRemovedWhenAppConfigUpdatedMessageWithInactiveStatusIsHandled(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantAppOnboardingTransfer = $this->tester->haveMerchantAppOnboardingPersisted([
            MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier,
        ]);

        $merchantAppOnboardingStatusTransfer = $this->tester->haveMerchantAppOnboardingStatusPersisted([
            MerchantAppOnboardingStatusTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantAppOnboardingStatusTransfer::MERCHANT_APP_ONBOARDING => [
                MerchantAppOnboardingTransfer::APP_IDENTIFIER => $merchantAppOnboardingTransfer->getAppIdentifier(),
                MerchantAppOnboardingTransfer::TYPE => $merchantAppOnboardingTransfer->getType(),
            ],
        ]);

        $appConfigUpdatedTransfer = $this->tester->haveAppConfigUpdatedTransfer([
            AppConfigUpdatedTransfer::IS_ACTIVE => false,
            AppConfigUpdatedTransfer::APP_IDENTIFIER => $appIdentifier,
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($appConfigUpdatedTransfer, 'app-events');

        // Assert
        $this->tester->dontSeeMerchantAppOnboardingStatusEntityInDatabase($merchantAppOnboardingStatusTransfer);
        $this->tester->dontSeeMerchantAppOnboardingEntityInDatabase($merchantAppOnboardingTransfer);
    }

    /**
     * @return void
     */
    public function testMerchantAppOnboardingDataStillExistsWhenAppConfigUpdatedMessageWithActiveStatusIsHandled(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantAppOnboardingTransfer = $this->tester->haveMerchantAppOnboardingPersisted([
            MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier,
        ]);

        $merchantAppOnboardingStatusTransfer = $this->tester->haveMerchantAppOnboardingStatusPersisted([
            MerchantAppOnboardingStatusTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantAppOnboardingStatusTransfer::MERCHANT_APP_ONBOARDING => [
                MerchantAppOnboardingTransfer::APP_IDENTIFIER => $merchantAppOnboardingTransfer->getAppIdentifier(),
                MerchantAppOnboardingTransfer::TYPE => $merchantAppOnboardingTransfer->getType(),
            ],
        ]);

        $appConfigUpdatedTransfer = $this->tester->haveAppConfigUpdatedTransfer([
            AppConfigUpdatedTransfer::IS_ACTIVE => true,
            AppConfigUpdatedTransfer::APP_IDENTIFIER => $appIdentifier,
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($appConfigUpdatedTransfer, 'app-events');

        // Assert
        $this->tester->seeMerchantAppOnboardingStatusEntityInDatabase($merchantAppOnboardingStatusTransfer);
        $this->tester->seeMerchantAppOnboardingEntityInDatabase($merchantAppOnboardingTransfer);
    }
}
