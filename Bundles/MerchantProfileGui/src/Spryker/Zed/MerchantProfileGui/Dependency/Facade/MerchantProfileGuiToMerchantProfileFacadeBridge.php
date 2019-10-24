<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;

class MerchantProfileGuiToMerchantProfileFacadeBridge implements MerchantProfileGuiToMerchantProfileFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface
     */
    protected $merchantProfileFacade;

    /**
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface $merchantProfileFacade
     */
    public function __construct($merchantProfileFacade)
    {
        $this->merchantProfileFacade = $merchantProfileFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function updateMerchantProfile(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        return $this->merchantProfileFacade->updateMerchantProfile($merchantProfileTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): ?MerchantProfileTransfer
    {
        return $this->merchantProfileFacade->findOne($merchantProfileCriteriaFilterTransfer);
    }
}
