<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Dependency\Facade;

interface SalesAggregatorToTaxInterface
{

    /**
     * @param string $grossPrice
     * @param int $taxRate
     * @param bool $round
     *
     * @return int
     */
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate, $round = true);

    /**
     * @api
     *
     * @param int $grossPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getAccruedTaxAmountFromGrossPrice($grossPrice, $taxRate);

}
