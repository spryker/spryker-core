<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding;

use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;
use Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface;

class MerchantAppOnboardingWriter implements MerchantAppOnboardingWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface
     */
    protected MerchantAppEntityManagerInterface $merchantAppEntityManager;

    /**
     * @param \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface $merchantAppEntityManager
     */
    public function __construct(MerchantAppEntityManagerInterface $merchantAppEntityManager)
    {
        $this->merchantAppEntityManager = $merchantAppEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer
     *
     * @return void
     */
    public function persistAppMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void
    {
        $this->merchantAppEntityManager->persistAppMerchantAppOnboarding($readyForMerchantAppOnboardingTransfer);
    }
}
