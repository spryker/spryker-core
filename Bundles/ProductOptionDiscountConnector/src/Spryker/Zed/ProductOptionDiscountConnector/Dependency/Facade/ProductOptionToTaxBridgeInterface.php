<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade;

interface ProductOptionToTaxBridgeInterface
{

    /**
     * @param int $grossPrice
     * @param float $taxRate
     * @param bool $round
     *
     * @return int
     */
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate, $round = true);

}
