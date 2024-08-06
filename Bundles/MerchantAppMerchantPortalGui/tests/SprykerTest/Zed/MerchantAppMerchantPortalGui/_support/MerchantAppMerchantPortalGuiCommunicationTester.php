<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\MerchantAppMerchantPortalGui;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Generated\Shared\Transfer\MerchantOnboardingContentTransfer;
use Generated\Shared\Transfer\MerchantOnboardingStateTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\MerchantApp\Business\MerchantAppFacade;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatusInterface;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacade;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantAppMerchantPortalGuiCommunicationTester extends Actor
{
    use _generated\MerchantAppMerchantPortalGuiCommunicationTesterActions;

    /**
     * @var array<array> $statusMapping
     */
    public array $statusMapping = [
        MerchantAppOnboardingStatusInterface::COMPLETED => [
            'displayText' => 'Your Stripe account has been successfully connected to the Marketplace account. You can get your payout.',
            'buttonText' => 'Disabled',
            'buttonInfo' => 'You are connected to the Marketplace account',
        ],
        MerchantAppOnboardingStatusInterface::ENABLED => [
            'displayText' => 'Your Stripe account has been successfully connected to the Marketplace account. Stripe may require more information once transaction volume increases.',
            'buttonText' => 'Update Profile',
            'buttonInfo' => 'You are connected to the Marketplace account',
        ],
        MerchantAppOnboardingStatusInterface::RESTRICTED => [
            'displayText' => 'Click the button below to get connected to the Marketplace accounts. This step is required for you to get your payout.',
            'buttonText' => 'Continue Onboarding',
            'buttonInfo' => 'You are required to provide more details to Stripe',
        ],
        MerchantAppOnboardingStatusInterface::RESTRICTED_SOON => [
            'displayText' => 'You are required to provide more details for your Stripe account. This step is required so that your payouts are not paused.',
            'buttonText' => 'Continue Onboarding',
            'buttonInfo' => 'You are required to provide more details to Stripe',
        ],
        MerchantAppOnboardingStatusInterface::PENDING => [
            'displayText' => 'Click the button below to get connected to the Marketplace accounts. This step is required for you to get your payout.',
            'buttonText' => 'Continue Onboarding',
            'buttonInfo' => 'Connect to the Marketplace account',
        ],
        MerchantAppOnboardingStatusInterface::REJECTED => [
            'displayText' => 'Your Stripe account has been rejected by the Marketplace. Payouts are paused. Please contact the Marketplace to resolve this.',
            'buttonText' => 'Continue Onboarding',
            'buttonInfo' => 'Connect to the Marketplace account',
        ],
    ];

    /**
     * @return void
     */
    public function mockMerchantUser(): void
    {
        $merchantTransfer = $this->haveMerchant();
        $merchantUserTransfer = new MerchantUserTransfer();
        $merchantUserTransfer->setMerchant($merchantTransfer);

        $merchantUserFacadeStub = Stub::make(MerchantUserFacade::class, [
            'getCurrentMerchantUser' => $merchantUserTransfer,
        ]);

        $this->addToLocatorCache('merchantUser-facade', $merchantUserFacadeStub);
    }

    /**
     * @param array $onboardingDetails
     *
     * @return void
     */
    public function mockMerchantAppOnboarding(array $onboardingDetails = []): void
    {
        $merchantOnboardingContentTransfer = new MerchantOnboardingContentTransfer();
        $merchantOnboardingContentTransfer->setMerchantOnboardingStates($this->getMerchantOnboardingStates());

        $onboardingDetails[MerchantAppOnboardingTransfer::ADDITIONAL_CONTENT] = $merchantOnboardingContentTransfer;
        $merchantAppOnboardingTransfer = $this->haveMerchantAppOnboarding($onboardingDetails);

        $merchantAppOnboardingCollectionTransfer = new MerchantAppOnboardingCollectionTransfer();
        $merchantAppOnboardingCollectionTransfer->addOnboarding($merchantAppOnboardingTransfer);

        $merchantAppFacadeStub = Stub::make(MerchantAppFacade::class, [
            'getMerchantAppOnboardingCollection' => $merchantAppOnboardingCollectionTransfer,
        ]);

        $this->addToLocatorCache('merchantApp-facade', $merchantAppFacadeStub);
    }

    /**
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MerchantOnboardingStateTransfer>
     */
    protected function getMerchantOnboardingStates(): ArrayObject
    {
        $merchantOnboardingStates = new ArrayObject();

        foreach ($this->statusMapping as $stateName => $attributes) {
            $merchantOnboardingStateTransfer = new MerchantOnboardingStateTransfer();
            $merchantOnboardingStateTransfer->setName($stateName);

            foreach ($attributes as $key => $value) {
                $merchantOnboardingStateTransfer->addAttribute($key, $value);
            }

            $merchantOnboardingStates->append($merchantOnboardingStateTransfer);
        }

        return $merchantOnboardingStates;
    }

    /**
     * @param string $status
     *
     * @return void
     */
    public function seeOnboardingStatus(string $status): void
    {
        $this->see($status);
    }

    /**
     * @param string $displayText
     *
     * @return void
     */
    public function seeDisplayText(string $displayText): void
    {
        $this->see($displayText);
    }

    /**
     * @param string $buttonText
     *
     * @return void
     */
    public function seeButtonText(string $buttonText): void
    {
        $this->see($buttonText, 'web-spy-button-link');
    }

    /**
     * @param string $buttonInfo
     *
     * @return void
     */
    public function seeButtonInfo(string $buttonInfo): void
    {
        $this->see($buttonInfo);
    }
}
