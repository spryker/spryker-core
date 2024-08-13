<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantAppMerchantPortalGui\Communication\Controller;

use Codeception\Stub;
use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Spryker\Zed\MerchantApp\Business\MerchantAppFacade;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatusInterface;
use SprykerTest\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantAppMerchantPortalGui
 * @group Communication
 * @group Controller
 * @group PaymentSettingsControllerCest
 * Add your own group annotations below this line
 */
class PaymentSettingsControllerCest
{
    /**
     * @param \SprykerTest\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiCommunicationTester $i
     *
     * @return void
     */
    public function givenTheMarketplaceDoesNotUseAPaymentAppWhenAMerchantNavigatesToThePaymentSettingsPageThenAnEmptyStateDesignIsDisplayed(
        MerchantAppMerchantPortalGuiCommunicationTester $i
    ): void {
        $i->mockMerchantUser();

        $merchantAppFacadeStub = Stub::make(MerchantAppFacade::class, [
            'getMerchantAppOnboardingCollection' => new MerchantAppOnboardingCollectionTransfer(),
        ]);

        $i->addToLocatorCache('merchantApp-facade', $merchantAppFacadeStub);

        $i->amOnPage('/merchant-app-merchant-portal-gui/payment-settings');
        $i->see('This Marketplace does not have any Payment Provider activated from the Spryker App Composition Platform.');
    }

    /**
     * @param \SprykerTest\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiCommunicationTester $i
     *
     * @return void
     */
    public function givenAMerchantHasNotStartedTheOnboardingProcessWhenAMerchantNavigatesToThePaymentSettingsPageThenTheStatusNotStartedIsDisplayedWithAMessageAndAStartOnboardingButton(
        MerchantAppMerchantPortalGuiCommunicationTester $i
    ): void {
        $i->mockMerchantUser();
        $i->mockMerchantAppOnboarding([
            MerchantAppOnboardingTransfer::APP_NAME => 'Awesome Payment Provider',
        ]);

        $i->amOnPage('/merchant-app-merchant-portal-gui/payment-settings');

        $i->seeOnboardingStatus('Not started');
        $i->seeDisplayText('Click the button below to get connected to the Marketplace account. This step is required for you to get your payout.');
        $i->seeButtonInfo('Start Onboarding');
    }

    /**
     * @param \SprykerTest\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiCommunicationTester $i
     *
     * @return void
     */
    public function givenAMerchantHasStartedTheOnboardingProcessAndItIsNotStartedWhenAMerchantNavigatesToThePaymentSettingsPageThenTheStatusNotStartedIsDisplayedWithAMessageAndAContinueOnboardingButton(
        MerchantAppMerchantPortalGuiCommunicationTester $i
    ): void {
        $i->mockMerchantUser();

        $i->mockMerchantAppOnboarding([
            MerchantAppOnboardingTransfer::APP_NAME => 'Awesome Payment Provider',
            MerchantAppOnboardingTransfer::STATUS => 'not started',
        ]);

        $i->amOnPage('/merchant-app-merchant-portal-gui/payment-settings');

        $i->seeOnboardingStatus('Not started');
        $i->seeDisplayText('Click the button below to get connected to the Marketplace account. This step is required for you to get your payout.');
        $i->seeButtonText('Start Onboarding');
    }

    /**
     * @param \SprykerTest\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiCommunicationTester $i
     *
     * @return void
     */
    public function validateUiTextsForStateEnabled(
        MerchantAppMerchantPortalGuiCommunicationTester $i
    ): void {
        $status = MerchantAppOnboardingStatusInterface::ENABLED;

        $i->mockMerchantUser();
        $i->mockMerchantAppOnboarding([
            MerchantAppOnboardingTransfer::APP_NAME => 'Awesome Payment Provider',
            MerchantAppOnboardingTransfer::STATUS => $status,
        ]);

        $i->amOnPage('/merchant-app-merchant-portal-gui/payment-settings');

        $i->seeOnboardingStatus($status);
        $i->seeDisplayText($i->statusMapping[$status]['displayText']);
        $i->seeButtonText($i->statusMapping[$status]['buttonText']);
        $i->seeButtonInfo($i->statusMapping[$status]['buttonInfo']);
    }

    /**
     * @param \SprykerTest\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiCommunicationTester $i
     *
     * @return void
     */
    public function validateUiTextsForStateRestricted(
        MerchantAppMerchantPortalGuiCommunicationTester $i
    ): void {
        $status = MerchantAppOnboardingStatusInterface::RESTRICTED;

        $i->mockMerchantUser();
        $i->mockMerchantAppOnboarding([
            MerchantAppOnboardingTransfer::APP_NAME => 'Awesome Payment Provider',
            MerchantAppOnboardingTransfer::STATUS => $status,
        ]);

        $i->amOnPage('/merchant-app-merchant-portal-gui/payment-settings');

        $i->seeOnboardingStatus($status);
        $i->seeDisplayText($i->statusMapping[$status]['displayText']);
        $i->seeButtonText($i->statusMapping[$status]['buttonText']);
        $i->seeButtonInfo($i->statusMapping[$status]['buttonInfo']);
    }

    /**
     * @param \SprykerTest\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiCommunicationTester $i
     *
     * @return void
     */
    public function validateUiTextsForStateRestrictedSoon(
        MerchantAppMerchantPortalGuiCommunicationTester $i
    ): void {
        $status = MerchantAppOnboardingStatusInterface::RESTRICTED_SOON;

        $i->mockMerchantUser();
        $i->mockMerchantAppOnboarding([
            MerchantAppOnboardingTransfer::APP_NAME => 'Awesome Payment Provider',
            MerchantAppOnboardingTransfer::STATUS => $status,
        ]);

        $i->amOnPage('/merchant-app-merchant-portal-gui/payment-settings');

        $i->seeOnboardingStatus($status);
        $i->seeDisplayText($i->statusMapping[$status]['displayText']);
        $i->seeButtonText($i->statusMapping[$status]['buttonText']);
        $i->seeButtonInfo($i->statusMapping[$status]['buttonInfo']);
    }

    /**
     * @param \SprykerTest\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiCommunicationTester $i
     *
     * @return void
     */
    public function validateUiTextsForStatePending(
        MerchantAppMerchantPortalGuiCommunicationTester $i
    ): void {
        $status = MerchantAppOnboardingStatusInterface::PENDING;

        $i->mockMerchantUser();
        $i->mockMerchantAppOnboarding([
            MerchantAppOnboardingTransfer::APP_NAME => 'Awesome Payment Provider',
            MerchantAppOnboardingTransfer::STATUS => $status,
        ]);

        $i->amOnPage('/merchant-app-merchant-portal-gui/payment-settings');

        $i->seeOnboardingStatus($status);
        $i->seeDisplayText($i->statusMapping[$status]['displayText']);
        $i->seeButtonText($i->statusMapping[$status]['buttonText']);
        $i->seeButtonInfo($i->statusMapping[$status]['buttonInfo']);
    }

    /**
     * @param \SprykerTest\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiCommunicationTester $i
     *
     * @return void
     */
    public function validateUiTextsForStateRejected(
        MerchantAppMerchantPortalGuiCommunicationTester $i
    ): void {
        $status = MerchantAppOnboardingStatusInterface::REJECTED;

        $i->mockMerchantUser();
        $i->mockMerchantAppOnboarding([
            MerchantAppOnboardingTransfer::APP_NAME => 'Awesome Payment Provider',
            MerchantAppOnboardingTransfer::STATUS => $status,
        ]);

        $i->amOnPage('/merchant-app-merchant-portal-gui/payment-settings');

        $i->seeOnboardingStatus($status);
        $i->seeDisplayText($i->statusMapping[$status]['displayText']);
        $i->seeButtonInfo($i->statusMapping[$status]['buttonInfo']);
    }
}
