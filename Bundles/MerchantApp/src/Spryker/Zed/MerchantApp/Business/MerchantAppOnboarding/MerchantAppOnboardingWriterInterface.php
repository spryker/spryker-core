<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding;

use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;

interface MerchantAppOnboardingWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer
     *
     * @return void
     */
    public function persistAppMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void;
}
