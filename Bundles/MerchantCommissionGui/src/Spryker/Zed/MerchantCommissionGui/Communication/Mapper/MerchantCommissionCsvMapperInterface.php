<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Mapper;

use Generated\Shared\Transfer\MerchantCommissionTransfer;

interface MerchantCommissionCsvMapperInterface
{
    /**
     * @param array<string, mixed> $merchantCommissionData
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function mapMerchantCommissionRowDataToMerchantCommissionTransfer(
        array $merchantCommissionData,
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer;
}
