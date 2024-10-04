<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusChangedTransfer;
use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantApp\Business\MerchantAppBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface getEntityManager()
 */
class MerchantAppFacade extends AbstractFacade implements MerchantAppFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer
     *
     * @return void
     */
    public function handleReadyForMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void
    {
        $this->getFactory()->createReadyForMerchantAppOnboardingMessageHandler()->handleReadyForMerchantAppOnboarding($readyForMerchantAppOnboardingTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingStatusChangedTransfer $merchantOnboardingFailedTransfer
     *
     * @return void
     */
    public function handleMerchantAppOnboardingStatusChanged(MerchantAppOnboardingStatusChangedTransfer $merchantOnboardingFailedTransfer): void
    {
        $this->getFactory()->createMerchantAppOnboardingChangedMessageHandler()->handleMerchantAppOnboardingStatusChanged($merchantOnboardingFailedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigUpdatedTransfer $appConfigUpdatedTransfer
     *
     * @return void
     */
    public function handleAppConfigUpdatedTransfer(AppConfigUpdatedTransfer $appConfigUpdatedTransfer): void
    {
        $this->getFactory()->createAppConfigUpdatedMessageHandler()
            ->handleAppConfigUpdatedTransfer($appConfigUpdatedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer
     */
    public function getMerchantAppOnboardingCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
    ): MerchantAppOnboardingCollectionTransfer {
        return $this->getFactory()->createMerchantAppOnboarding()->getMerchantAppOnboardingCollection($merchantAppOnboardingCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer
     */
    public function initializeMerchantAppOnboarding(
        MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
    ): MerchantAppOnboardingInitializationResponseTransfer {
        return $this->getFactory()->createMerchantAppOnboarding()->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    public function addMerchantReferenceHeader(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer
    {
        return $this->getFactory()->createRequest()->addMerchantReferenceHeader($acpHttpRequestTransfer);
    }
}
