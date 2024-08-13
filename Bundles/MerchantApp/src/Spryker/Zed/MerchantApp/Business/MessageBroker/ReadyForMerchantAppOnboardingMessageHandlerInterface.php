<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MessageBroker;

use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;

interface ReadyForMerchantAppOnboardingMessageHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer
     *
     * @return void
     */
    public function handleReadyForMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void;
}
