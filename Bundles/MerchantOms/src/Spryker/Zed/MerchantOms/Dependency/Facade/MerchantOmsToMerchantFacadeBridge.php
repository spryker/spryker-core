<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

class MerchantOmsToMerchantFacadeBridge implements MerchantOmsToMerchantFacadeInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\Merchant\Business\MerchantFacadeInterface $merchantFacade
     */
    public function __construct($merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @inheritDoc
     */
    public function findOne(MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer): ?MerchantTransfer
    {
        return $this->merchantFacade->findOne($merchantCriteriaFilterTransfer);
    }
}
