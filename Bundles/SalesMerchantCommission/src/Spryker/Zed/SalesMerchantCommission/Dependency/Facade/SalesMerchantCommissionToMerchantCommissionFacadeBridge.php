<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer;

class SalesMerchantCommissionToMerchantCommissionFacadeBridge implements SalesMerchantCommissionToMerchantCommissionFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface
     */
    protected $merchantCommissionFacade;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface $merchantCommissionFacade
     */
    public function __construct($merchantCommissionFacade)
    {
        $this->merchantCommissionFacade = $merchantCommissionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer
     */
    public function calculateMerchantCommission(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): MerchantCommissionCalculationResponseTransfer {
        return $this->merchantCommissionFacade
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);
    }
}
