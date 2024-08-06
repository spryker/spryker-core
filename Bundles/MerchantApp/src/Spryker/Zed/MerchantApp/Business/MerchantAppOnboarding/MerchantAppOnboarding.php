<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Spryker\Shared\MerchantApp\Message\MerchantAppMessage;
use Spryker\Zed\MerchantApp\Business\Exception\MerchantAppOnboardingLogicException;
use Spryker\Zed\MerchantApp\Business\Exception\MerchantAppOnboardingNotFoundException;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToKernelAppFacadeInterface;
use Spryker\Zed\MerchantApp\MerchantAppConfig;
use Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface;
use Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface;

class MerchantAppOnboarding implements MerchantAppOnboardingInterface
{
    /**
     * @var string
     */
    public const STRATEGY_API = 'api';

    /**
     * @var string
     */
    public const STRATEGY_IFRAME = 'iframe';

    /**
     * @var string
     */
    public const STRATEGY_CONTENT = 'content';

    /**
     * @var string
     */
    public const STRATEGY_REDIRECT = 'redirect';

    /**
     * @var \Spryker\Zed\MerchantApp\MerchantAppConfig
     */
    protected MerchantAppConfig $merchantAppConfig;

    /**
     * @var \Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface
     */
    protected MerchantAppRepositoryInterface $merchantAppRepository;

    /**
     * @var \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface
     */
    protected MerchantAppEntityManagerInterface $merchantAppEntityManager;

    /**
     * @var \Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToKernelAppFacadeInterface
     */
    protected MerchantAppToKernelAppFacadeInterface $kernelAppFacade;

    /**
     * @param \Spryker\Zed\MerchantApp\MerchantAppConfig $merchantAppConfig
     * @param \Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface $merchantAppRepository
     * @param \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface $merchantAppEntityManager
     * @param \Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToKernelAppFacadeInterface $kernelAppFacade
     */
    public function __construct(
        MerchantAppConfig $merchantAppConfig,
        MerchantAppRepositoryInterface $merchantAppRepository,
        MerchantAppEntityManagerInterface $merchantAppEntityManager,
        MerchantAppToKernelAppFacadeInterface $kernelAppFacade
    ) {
        $this->merchantAppConfig = $merchantAppConfig;
        $this->merchantAppRepository = $merchantAppRepository;
        $this->merchantAppEntityManager = $merchantAppEntityManager;
        $this->kernelAppFacade = $kernelAppFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer
     */
    public function getMerchantAppOnboardingCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
    ): MerchantAppOnboardingCollectionTransfer {
        return $this->merchantAppRepository->getMerchantAppOnboardingCollection($merchantAppOnboardingStatusCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
     *
     * @throws \Spryker\Zed\MerchantApp\Business\Exception\MerchantAppOnboardingNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer
     */
    public function initializeMerchantAppOnboarding(
        MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
    ): MerchantAppOnboardingInitializationResponseTransfer {
        $this->validateMerchantAppOnboardingInitializationRequest($merchantAppOnboardingInitializationRequestTransfer);

        $merchantAppOnboardingStatusCriteriaTransfer = (new MerchantAppOnboardingCriteriaTransfer())
            ->addAppIdentifier($merchantAppOnboardingInitializationRequestTransfer->getAppIdentifierOrFail())
            ->setType($merchantAppOnboardingInitializationRequestTransfer->getTypeOrFail())
            ->setMerchant($merchantAppOnboardingInitializationRequestTransfer->getMerchantOrFail());

        $merchantAppOnboardingTransferCollection = $this->merchantAppRepository->getMerchantAppOnboardingCollection($merchantAppOnboardingStatusCriteriaTransfer);

        if ($merchantAppOnboardingTransferCollection->getOnboardings()->count() === 0) {
            throw new MerchantAppOnboardingNotFoundException(MerchantAppMessage::getMerchantAppOnboardingNotFoundExceptionMessage(
                $merchantAppOnboardingInitializationRequestTransfer->getAppIdentifierOrFail(),
                $merchantAppOnboardingInitializationRequestTransfer->getTypeOrFail(),
            ));
        }

        /** @var \Generated\Shared\Transfer\MerchantAppOnboardingTransfer $merchantAppOnboardingTransfer */
        $merchantAppOnboardingTransfer = $merchantAppOnboardingTransferCollection->getOnboardings()->offsetGet(0);

        $merchantAppOnboardingInitializationResponseTransfer = $this->getMerchantAppOnboardingInitializationResponse(
            $merchantAppOnboardingTransfer,
            $merchantAppOnboardingInitializationRequestTransfer,
        );

        if ($merchantAppOnboardingTransfer->getStatus() === null) {
            $merchantAppOnboardingStatusTransfer = (new MerchantAppOnboardingStatusTransfer())
                ->setMerchantAppOnboarding($merchantAppOnboardingTransfer)
                ->setMerchantReference($merchantAppOnboardingInitializationRequestTransfer->getMerchantOrFail()->getMerchantReferenceOrFail())
                ->setStatus(MerchantAppOnboardingStatusInterface::INCOMPLETE);

            $this->merchantAppEntityManager->persistAppMerchantAppOnboardingStatus($merchantAppOnboardingStatusTransfer);
        }

        return $merchantAppOnboardingInitializationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
     *
     * @throws \Spryker\Zed\MerchantApp\Business\Exception\MerchantAppOnboardingLogicException
     *
     * @return void
     */
    protected function validateMerchantAppOnboardingInitializationRequest(
        MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
    ): void {
        if (!$merchantAppOnboardingInitializationRequestTransfer->getType()) {
            throw new MerchantAppOnboardingLogicException(MerchantAppMessage::getMerchantAppOnboardingMissingOnboardingTypeExceptionMessage());
        }
        if (!$merchantAppOnboardingInitializationRequestTransfer->getAppIdentifier()) {
            throw new MerchantAppOnboardingLogicException(MerchantAppMessage::getMerchantAppOnboardingMissingAppIdentifierExceptionMessage());
        }
        if (!$merchantAppOnboardingInitializationRequestTransfer->getMerchant() || !$merchantAppOnboardingInitializationRequestTransfer->getMerchant()->getMerchantReference()) {
            throw new MerchantAppOnboardingLogicException(MerchantAppMessage::getMerchantAppOnboardingMissingMerchantExceptionMessage());
        }
    }

    /**
     * IFrame and redirect strategies can return an immediate response. Render the iframe or performing a redirect is handled by the requesting module.
     *
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingTransfer $merchantAppOnboardingTransfer
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer
     */
    protected function getMerchantAppOnboardingInitializationResponse(
        MerchantAppOnboardingTransfer $merchantAppOnboardingTransfer,
        MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
    ): MerchantAppOnboardingInitializationResponseTransfer {
        $merchantAppOnboardingInitializationResponseTransfer = new MerchantAppOnboardingInitializationResponseTransfer();

        if ($merchantAppOnboardingTransfer->getOnboardingOrFail()->getStrategy() === static::STRATEGY_API) {
            $bodyData = [
                'merchant' => $merchantAppOnboardingInitializationRequestTransfer->getMerchant(),
                'successUrl' => $merchantAppOnboardingInitializationRequestTransfer->getSuccessUrl(),
                'refreshUrl' => $merchantAppOnboardingInitializationRequestTransfer->getRefreshUrl(),
                'cancelUrl' => $merchantAppOnboardingInitializationRequestTransfer->getCancelUrl(),
                'errorUrl' => $merchantAppOnboardingInitializationRequestTransfer->getErrorUrl(),
            ];

            $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
            $acpHttpRequestTransfer
                ->setMethod('POST')
                ->setUri($merchantAppOnboardingTransfer->getOnboardingOrFail()->getUrlOrFail())
                ->setBody((string)json_encode($bodyData));

            $acpHttpResponseTransfer = $this->kernelAppFacade->makeRequest($acpHttpRequestTransfer);
            $content = $acpHttpResponseTransfer->getContentOrFail();
            $content = json_decode($content, true);

            $merchantAppOnboardingInitializationResponseTransfer
                ->setUrl($content['url'])
                ->setStrategy($content['strategy'])
                ->setContent($content['content'] ?? null);

            return $merchantAppOnboardingInitializationResponseTransfer;
        }

        $merchantAppOnboardingInitializationResponseTransfer
            ->setUrl($merchantAppOnboardingTransfer->getOnboardingOrFail()->getUrl())
            ->setStrategy($merchantAppOnboardingTransfer->getOnboardingOrFail()->getStrategy());

        return $merchantAppOnboardingInitializationResponseTransfer;
    }
}
