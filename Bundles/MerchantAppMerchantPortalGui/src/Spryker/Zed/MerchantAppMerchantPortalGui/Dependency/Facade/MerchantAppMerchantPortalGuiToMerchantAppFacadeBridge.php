<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAppMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer;

class MerchantAppMerchantPortalGuiToMerchantAppFacadeBridge implements MerchantAppMerchantPortalGuiToMerchantAppFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantApp\Business\MerchantAppFacadeInterface
     */
    protected $merchantAppFacade;

    /**
     * @param \Spryker\Zed\MerchantApp\Business\MerchantAppFacadeInterface $merchantAppFacade
     */
    public function __construct($merchantAppFacade)
    {
        $this->merchantAppFacade = $merchantAppFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer
     */
    public function getMerchantAppOnboardingCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
    ): MerchantAppOnboardingCollectionTransfer {
        return $this->merchantAppFacade->getMerchantAppOnboardingCollection($merchantAppOnboardingCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer
     */
    public function initializeMerchantAppOnboarding(
        MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
    ): MerchantAppOnboardingInitializationResponseTransfer {
        return $this->merchantAppFacade->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);
    }
}
