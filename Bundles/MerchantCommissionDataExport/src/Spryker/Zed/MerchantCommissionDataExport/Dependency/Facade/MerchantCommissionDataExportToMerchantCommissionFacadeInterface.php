<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer;

interface MerchantCommissionDataExportToMerchantCommissionFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
     *
     * @return float
     */
    public function transformMerchantCommissionAmountFromPersistence(
        MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
    ): float;
}
