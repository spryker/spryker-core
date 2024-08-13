<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MessageBroker;

use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingWriterInterface;

class ReadyForMerchantAppOnboardingMessageHandler implements ReadyForMerchantAppOnboardingMessageHandlerInterface
{
    /**
     * @var \Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingWriterInterface
     */
    protected MerchantAppOnboardingWriterInterface $merchantAppOnboardingDetails;

    /**
     * @param \Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingWriterInterface $merchantAppOnboardingDetails
     */
    public function __construct(MerchantAppOnboardingWriterInterface $merchantAppOnboardingDetails)
    {
        $this->merchantAppOnboardingDetails = $merchantAppOnboardingDetails;
    }

    /**
     * @param \Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer
     *
     * @return void
     */
    public function handleReadyForMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void
    {
        $this->merchantAppOnboardingDetails->persistAppMerchantAppOnboarding($readyForMerchantAppOnboardingTransfer);
    }
}
