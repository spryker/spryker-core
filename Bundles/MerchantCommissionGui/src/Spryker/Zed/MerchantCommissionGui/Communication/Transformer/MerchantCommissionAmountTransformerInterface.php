<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Transformer;

interface MerchantCommissionAmountTransformerInterface
{
    /**
     * @param string $merchantCommissionCalculatorPluginType
     * @param float $amount
     *
     * @return int
     */
    public function transformMerchantCommissionAmount(
        string $merchantCommissionCalculatorPluginType,
        float $amount
    ): int;
}
