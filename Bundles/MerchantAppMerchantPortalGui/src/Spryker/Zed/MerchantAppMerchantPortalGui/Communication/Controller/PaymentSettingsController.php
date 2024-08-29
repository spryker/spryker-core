<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAppMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer;
use Generated\Shared\Transfer\MerchantOnboardingContentTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatusInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantAppMerchantPortalGui\Communication\MerchantAppMerchantPortalGuiCommunicationFactory getFactory()
 */
class PaymentSettingsController extends AbstractController
{
    /**
     * @var string
     */
    protected const MERCHANT_ONBOARDING_TYPE = 'payment';

    /**
     * @var string
     */
    protected const CONTROLLER_URL_PATH = '/merchant-app-merchant-portal-gui/payment-settings';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function indexAction(Request $request): array
    {
        $merchantTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getMerchantOrFail();

        $merchantAppOnboardingCriteriaTransfer = (new MerchantAppOnboardingCriteriaTransfer())
            ->setMerchant($merchantTransfer)
            ->setType(static::MERCHANT_ONBOARDING_TYPE);

        $merchantAppOnboardingCollection = $this->getFactory()->getMerchantAppFacade()
            ->getMerchantAppOnboardingCollection($merchantAppOnboardingCriteriaTransfer);

        $paymentProviders = [];

        foreach ($merchantAppOnboardingCollection->getOnboardings() as $merchantAppOnboardingTransfer) {
            $additionalContentTransfer = $merchantAppOnboardingTransfer->getAdditionalContent();

            $merchantOnboardingStates = new ArrayObject();

            if ($additionalContentTransfer instanceof MerchantOnboardingContentTransfer && $additionalContentTransfer->getMerchantOnboardingStates()->count() !== 0) {
                $merchantOnboardingStates = $additionalContentTransfer->getMerchantOnboardingStates();
            }

            $status = $merchantAppOnboardingTransfer->getStatus() ?? 'Not started';
            $showButton = $status !== MerchantAppOnboardingStatusInterface::COMPLETED && $status !== MerchantAppOnboardingStatusInterface::REJECTED;

            $statusDetails = $this->getStatusDetails($status, $merchantOnboardingStates);
            $status = $statusDetails['statusText'] ?? $status;

            $paymentProviders[] = [
                'title' => $merchantAppOnboardingTransfer->getAppName(),
                'status' => ucfirst($status),
                'actionButtons' => ($status === MerchantAppOnboardingStatusInterface::COMPLETED
                    ? []
                    : [
                        [
                            'label' => $statusDetails['buttonText'] ?? $this->getActionButtonLabel($status),
                            'url' => sprintf(
                                static::CONTROLLER_URL_PATH . '/onboarding?app-identifier=%s',
                                $merchantAppOnboardingTransfer->getAppIdentifier(),
                            ),
                        ],
                    ]
                ),
                'additionalContent' => '',
                'additionalLinks' => $this->prepareAdditionalLinks($merchantAppOnboardingTransfer->getAdditionalContent(), $merchantTransfer),
                'showButton' => $showButton,
                'displayText' => $statusDetails['displayText'] ?? sprintf('Click the button below to get connected to the Marketplace account. This step is required for you to get your payout.'),
                'buttonInfo' => $statusDetails['buttonInfo'] ?? 'Connect to the Marketplace account',
                'colorChip' => $this->getColorForStatus($status),
            ];
        }

        return ['paymentProviders' => $paymentProviders];
    }

    /**
     * @param string $status
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MerchantOnboardingStateTransfer> $merchantOnboardingStateTransfers
     *
     * @return array<string, string>
     */
    protected function getStatusDetails(string $status, ArrayObject $merchantOnboardingStateTransfers): array
    {
        foreach ($merchantOnboardingStateTransfers as $merchantOnboardingStateTransfer) {
            if ($merchantOnboardingStateTransfer->getName() === $status) {
                return $merchantOnboardingStateTransfer->getAttributes();
            }
        }

        return [];
    }

    /**
     * @param string|null $status
     *
     * @return string
     */
    protected function getActionButtonLabel(?string $status): string
    {
        return match ($status) {
            MerchantAppOnboardingStatusInterface::INCOMPLETE => 'Continue Onboarding',
            default => 'Start Onboarding',
        };
    }

    /**
     * @param string $status
     *
     * @return string
     */
    protected function getColorForStatus(string $status): string
    {
        return match ($status) {
            MerchantAppOnboardingStatusInterface::ENABLED => 'green',
            MerchantAppOnboardingStatusInterface::PENDING => 'yellow',
            MerchantAppOnboardingStatusInterface::RESTRICTED => 'red',
            MerchantAppOnboardingStatusInterface::RESTRICTED_SOON => 'red',
            MerchantAppOnboardingStatusInterface::INCOMPLETE => 'red',
            MerchantAppOnboardingStatusInterface::COMPLETED => 'green',
            default => 'grey',
        };
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function onboardingAction(Request $request): array|RedirectResponse
    {
        $appIdentifier = (string)$request->query->get('app-identifier');

        $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();
        $merchantTransfer = $merchantUserTransfer->getMerchantOrFail();

        $baseUrl = rtrim($this->getFactory()->getConfig()->getMerchantPortalBaseUrl(), '/');
        $merchantAppOnboardingInitializationRequestTransfer = (new MerchantAppOnboardingInitializationRequestTransfer())
            ->setMerchant($merchantTransfer)
            ->setLocaleName($merchantUserTransfer->getUserOrFail()->getLocaleName())
            ->setAppIdentifier($appIdentifier)
            ->setSuccessUrl($baseUrl . '/redirect.php?url=' . urlencode(static::CONTROLLER_URL_PATH . '/?app-identifier=' . $appIdentifier))
            ->setRefreshUrl($baseUrl . '/redirect.php?url=' . urlencode(static::CONTROLLER_URL_PATH . '/onboarding/?app-identifier=' . $appIdentifier))
            ->setType(static::MERCHANT_ONBOARDING_TYPE);

        $merchantAppOnboardingInitializationResponseTransfer = $this->getFactory()->getMerchantAppFacade()->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);

        if ($merchantAppOnboardingInitializationResponseTransfer->getStrategy() === 'redirect') {
            return new RedirectResponse($merchantAppOnboardingInitializationResponseTransfer->getUrlOrFail());
        }

        return [
            'strategy' => $merchantAppOnboardingInitializationResponseTransfer->getStrategy(),
            'url' => $merchantAppOnboardingInitializationResponseTransfer->getUrl(),
            'content' => $merchantAppOnboardingInitializationResponseTransfer->getContent(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOnboardingContentTransfer|null $merchantOnboardingContentTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LinkTransfer>
     */
    protected function prepareAdditionalLinks(
        ?MerchantOnboardingContentTransfer $merchantOnboardingContentTransfer,
        MerchantTransfer $merchantTransfer
    ): ArrayObject {
        if ($merchantOnboardingContentTransfer === null || $merchantOnboardingContentTransfer->getLinks()->count() === 0) {
            return new ArrayObject();
        }

        foreach ($merchantOnboardingContentTransfer->getLinks() as $linkTransfer) {
            $url = str_replace('_merchantReference_', $merchantTransfer->getMerchantReferenceOrFail(), $linkTransfer->getUrlOrFail());
            $linkTransfer->setUrl($url);
        }

        return $merchantOnboardingContentTransfer->getLinks();
    }
}
