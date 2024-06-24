<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer;

class MerchantCommissionDataExportToMerchantCommissionFacadeBridge implements MerchantCommissionDataExportToMerchantCommissionFacadeInterface
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
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
     *
     * @return float
     */
    public function transformMerchantCommissionAmountFromPersistence(
        MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
    ): float {
        return $this->merchantCommissionFacade->transformMerchantCommissionAmountFromPersistence(
            $merchantCommissionAmountTransformerRequestTransfer,
        );
    }
}
