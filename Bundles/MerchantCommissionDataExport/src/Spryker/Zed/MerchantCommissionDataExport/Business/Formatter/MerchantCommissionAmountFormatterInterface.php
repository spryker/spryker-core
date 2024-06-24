<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business\Formatter;

interface MerchantCommissionAmountFormatterInterface
{
    /**
     * @param array<string, mixed> $merchantCommissionData
     *
     * @return array<string, mixed>
     */
    public function formatMerchantCommissionAmount(array $merchantCommissionData): array;
}
