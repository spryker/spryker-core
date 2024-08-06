<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\MerchantApp\MerchantAppTests\AppEvents;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Generated\Shared\Transfer\MerchantOnboardingStateTransfer;
use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;
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
 * @group ReadyForMerchantAppOnboardingTest
 * Add your own group annotations below this line
 */
class ReadyForMerchantAppOnboardingTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\MerchantApp\MerchantAppAsyncApiTester
     */
    protected MerchantAppAsyncApiTester $tester;

    /**
     * @return void
     */
    public function testGivenAnAppSendsAReadyForMerchantAppOnboardingMessageWithAStatusMapWhenIHandleTheMessageThenInformationAboutTheAppThatIsReadyToOnboardMerchantsIsPersisted(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantOnboardingStateTransfer = new MerchantOnboardingStateTransfer();
        $merchantOnboardingStateTransfer
            ->setName('foo')
            ->addAttribute('bar', 'baz');

        $readyForMerchantAppOnboardingTransfer = $this->tester->haveReadyForMerchantAppOnboardingTransfer([
            ReadyForMerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier,
            ReadyForMerchantAppOnboardingTransfer::MERCHANT_ONBOARDING_STATES => [
                $merchantOnboardingStateTransfer,
            ],
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($readyForMerchantAppOnboardingTransfer, 'merchant-app-events');

        // Assert
        $this->tester->seeMerchantAppOnboardingEntityInDatabase($readyForMerchantAppOnboardingTransfer);
    }

    /**
     * @return void
     */
    public function testGivenAMerchantOnboardingAlreadyExistsAndAnAppSendsAReadyForMerchantAppOnboardingMessageAgainWhenIHandleTheMessageThenInformationAboutTheAppThatIsReadyToOnboardMerchantsIsUpdated(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantAppOnboardingTransfer = $this->tester->haveMerchantAppOnboardingPersisted([
            MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier,
        ]);

        $merchantOnboardingStateTransfer = new MerchantOnboardingStateTransfer();
        $merchantOnboardingStateTransfer
            ->setName('foo')
            ->addAttribute('bar', 'baz');

        $readyForMerchantAppOnboardingTransfer = $this->tester->haveReadyForMerchantAppOnboardingTransfer([
            ReadyForMerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier,
            ReadyForMerchantAppOnboardingTransfer::APP_NAME => $merchantAppOnboardingTransfer->getAppName(),
            ReadyForMerchantAppOnboardingTransfer::MERCHANT_ONBOARDING_STATES => [
                $merchantOnboardingStateTransfer,
            ],
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($readyForMerchantAppOnboardingTransfer, 'merchant-app-events');

        // Assert
        $this->tester->seeMerchantAppOnboardingEntityInDatabase($readyForMerchantAppOnboardingTransfer);
    }
}
