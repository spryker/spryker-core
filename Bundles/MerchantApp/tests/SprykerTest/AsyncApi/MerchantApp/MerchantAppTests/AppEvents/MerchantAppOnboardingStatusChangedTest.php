<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\MerchantApp\MerchantAppTests\AppEvents;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusChangedTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatusInterface;
use SprykerTest\AsyncApi\MerchantApp\MerchantAppAsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group MerchantApp
 * @group MerchantAppTests
 * @group AppEvents
 * @group MerchantAppOnboardingStatusChangedTest
 * Add your own group annotations below this line
 */
class MerchantAppOnboardingStatusChangedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\MerchantApp\MerchantAppAsyncApiTester
     */
    protected MerchantAppAsyncApiTester $tester;

    /**
     * @return void
     */
    public function testGivenTheOnboardingIsInitializedWhenIHandleTheMerchantAppOnboardingStatusChangedMessageAndTheNewStatusIsCompletedThenTheStatusOfTheOnboardingIsSetToCompleted(): void
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

        $merchantOnboardingStatusChangedTransfer = $this->tester->haveMerchantAppOnboardingStatusChangedTransfer([
            MerchantAppOnboardingStatusChangedTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantAppOnboardingStatusChangedTransfer::APP_IDENTIFIER => $merchantAppOnboardingTransfer->getAppIdentifier(),
            MerchantAppOnboardingStatusChangedTransfer::STATUS => MerchantAppOnboardingStatusInterface::COMPLETED,
            MerchantAppOnboardingStatusChangedTransfer::TYPE => 'merchant-app-onboarding',
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($merchantOnboardingStatusChangedTransfer, 'merchant-app-events');

        // Assert
        $this->tester->seeMerchantAppOnboardingStatusEntityInDatabase($merchantAppOnboardingStatusTransfer, MerchantAppOnboardingStatusInterface::COMPLETED);
    }

    /**
     * @return void
     */
    public function testGivenTheOnboardingIsInitializedWhenIHandleTheMerchantAppOnboardingStatusChangedMessageAndTheNewStatusIsRestrictedTheStatusIsSetToRestricted(): void
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

        $merchantOnboardingStatusChangedTransfer = $this->tester->haveMerchantAppOnboardingStatusChangedTransfer([
            MerchantAppOnboardingStatusChangedTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantAppOnboardingStatusChangedTransfer::STATUS => MerchantAppOnboardingStatusInterface::RESTRICTED,
            MerchantAppOnboardingStatusChangedTransfer::APP_IDENTIFIER => $merchantAppOnboardingTransfer->getAppIdentifier(),
            MerchantAppOnboardingStatusChangedTransfer::TYPE => 'merchant-app-onboarding',
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($merchantOnboardingStatusChangedTransfer, 'merchant-app-events');

        // Assert
        $this->tester->seeMerchantAppOnboardingStatusEntityInDatabase($merchantAppOnboardingStatusTransfer, MerchantAppOnboardingStatusInterface::RESTRICTED);
    }

    /**
     * @return void
     */
    public function testGivenTheOnboardingIsNotInitializedWhenIHandleTheMerchantAppOnboardingStatusChangedMessageThenTheMessageIsIgnored(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantAppOnboardingTransfer = $this->tester->haveMerchantAppOnboardingPersisted([
            MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier,
        ]);

        $merchantOnboardingStatusChangedTransfer = $this->tester->haveMerchantAppOnboardingStatusChangedTransfer([
            MerchantAppOnboardingStatusChangedTransfer::MERCHANT_REFERENCE => 'does not exist',
            MerchantAppOnboardingStatusChangedTransfer::APP_IDENTIFIER => $merchantAppOnboardingTransfer->getAppIdentifier(),
            MerchantAppOnboardingStatusChangedTransfer::STATUS => MerchantAppOnboardingStatusInterface::RESTRICTED,
            MerchantAppOnboardingStatusChangedTransfer::TYPE => 'merchant-app-onboarding',
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($merchantOnboardingStatusChangedTransfer, 'merchant-app-events');

        // Assert
        // No actual test needed this is only for coverage and the case the message refers to a not existing entity
    }
}
