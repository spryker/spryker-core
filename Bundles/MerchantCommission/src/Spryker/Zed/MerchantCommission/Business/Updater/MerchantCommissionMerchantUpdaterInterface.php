<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Updater;

use Generated\Shared\Transfer\MerchantCommissionTransfer;

interface MerchantCommissionMerchantUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function updateMerchantCommissionMerchantRelations(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer;
}
