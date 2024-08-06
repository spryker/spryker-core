<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantApp\Message;

use Generated\Shared\Transfer\MerchantTransfer;

class MerchantAppMessage
{
    /**
     * @param string $appIdentifier
     * @param string $type
     *
     * @return string
     */
    public static function getMerchantAppOnboardingNotFoundExceptionMessage(string $appIdentifier, string $type): string
    {
        return sprintf('Could not find a MerchantAppOnboard for the App Identifier "%s" and the type "%s"', $appIdentifier, $type);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return string
     */
    public static function getMerchantAppOnboardingAlreadyInitializedExceptionMessage(MerchantTransfer $merchantTransfer): string
    {
        return sprintf('The Onboarding for the Merchant with reference "%s" was already initialized.', $merchantTransfer->getMerchantReference());
    }

    /**
     * @return string
     */
    public static function getMerchantAppOnboardingMissingOnboardingTypeExceptionMessage(): string
    {
        return 'The onboarding type is missing and must be passed. Without an onboarding type the details can not be safely fetched as each App can have multiple onboardings.';
    }

    /**
     * @return string
     */
    public static function getMerchantAppOnboardingMissingAppIdentifierExceptionMessage(): string
    {
        return 'The AppIdentifier is missing and must be passed. Without an appIdentifier the details can not be safely fetched as all Apps would be returned that do match the other criteria.';
    }

    /**
     * @return string
     */
    public static function getMerchantAppOnboardingMissingMerchantExceptionMessage(): string
    {
        return 'The Merchant is missing and must be passed. Without a Merchant and a merchantReference the status of the onboarding can not be safely fetched.';
    }
}
