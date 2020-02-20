<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;

class MerchantProfileGuiPageToMerchantUserFacadeBridge implements MerchantProfileGuiPageToMerchantUserFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct($merchantUserFacade)
    {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(
        MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
    ): ?MerchantUserTransfer {
        return $this->merchantUserFacade->findOne($merchantUserCriteriaFilterTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser(): UserTransfer
    {
        return $this->merchantUserFacade->getCurrentUser();
    }
}
