<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade;

interface PriceProductMerchantCommissionConnectorToMoneyFacadeInterface
{
    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger(float $value): int;
}
