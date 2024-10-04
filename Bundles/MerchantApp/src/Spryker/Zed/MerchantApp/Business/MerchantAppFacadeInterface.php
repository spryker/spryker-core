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

interface MerchantAppFacadeInterface
{
    /**
     * Specification:
     * - When an App sends the ReadyForMerchantAppOnboarding message, this handler will be triggered.
     * - All information from the App will be persisted in the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer
     *
     * @return void
     */
    public function handleReadyForMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void;

    /**
     * Specification:
     * - When an App sends the MerchantAppOnboardingStatusChanged message, this handler will be triggered.
     * - The status of the onboarding will be updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingStatusChangedTransfer $merchantOnboardingFailedTransfer
     *
     * @return void
     */
    public function handleMerchantAppOnboardingStatusChanged(MerchantAppOnboardingStatusChangedTransfer $merchantOnboardingFailedTransfer): void;

    /**
     * Specification:
     * - When an App sends the AppConfigUpdatedTransfer message, this handler will be triggered.
     * - Requires AppConfigUpdated.isActive to be set.
     * - Does nothing if AppConfigUpdated.isActive === true.
     * - Otherwise, requires AppConfigUpdated.appIdentifier to be set and removes all the MerchantApp onboarding data by AppConfigUpdated.appIdentifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigUpdatedTransfer $appConfigUpdatedTransfer
     *
     * @return void
     */
    public function handleAppConfigUpdatedTransfer(AppConfigUpdatedTransfer $appConfigUpdatedTransfer): void;

    /**
     * Specification:
     * - Retrieves the onboarding details and status if exists.
     * - Can be used in different ways through the Criteria object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer
     */
    public function getMerchantAppOnboardingCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
    ): MerchantAppOnboardingCollectionTransfer;

    /**
     * Specification:
     * - Initialize the onboarding process of a Merchant to a specific app.
     * - Requires `MerchantAppOnboardingInitializationRequestTransfer::MERCHANT` to be set.
     * - Requires `MerchantAppOnboardingInitializationRequestTransfer::APP_IDENTIFIER` to be set.
     * - Returns a `MerchantAppOnboardingInitializationResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer
     */
    public function initializeMerchantAppOnboarding(
        MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
    ): MerchantAppOnboardingInitializationResponseTransfer;

    /**
     *  Specification:
     *  - Adds the MerchantReference to the `AcpRequestRansfer` header when a current merchant user is available.
     *  - Returns a `AcpRequestRansfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    public function addMerchantReferenceHeader(
        AcpHttpRequestTransfer $acpHttpRequestTransfer
    ): AcpHttpRequestTransfer;
}
