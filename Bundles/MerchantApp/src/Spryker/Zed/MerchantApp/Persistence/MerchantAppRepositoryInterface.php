<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Persistence;

use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusCollectionTransfer;

interface MerchantAppRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingStatusCollectionTransfer
     */
    public function getMerchantAppOnboardingStatusCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
    ): MerchantAppOnboardingStatusCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer
     */
    public function getMerchantAppOnboardingCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
    ): MerchantAppOnboardingCollectionTransfer;
}
