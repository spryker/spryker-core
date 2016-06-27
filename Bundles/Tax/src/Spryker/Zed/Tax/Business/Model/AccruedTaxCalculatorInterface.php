<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Tax\Business\Model;

interface AccruedTaxCalculatorInterface
{

    /**
     *
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxPercentage Tax percentage as float (e. g. 19.6)
     *
     * @return int
     */
    public function getTaxValueFromPrice($price, $taxRate);

}
