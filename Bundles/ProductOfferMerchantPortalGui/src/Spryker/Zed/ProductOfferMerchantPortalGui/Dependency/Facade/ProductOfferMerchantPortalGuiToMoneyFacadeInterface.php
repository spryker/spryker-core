<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade;

interface ProductOfferMerchantPortalGuiToMoneyFacadeInterface
{
    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value);

    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger($value);
}
