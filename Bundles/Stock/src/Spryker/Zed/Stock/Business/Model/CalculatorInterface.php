<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Business\Model;

interface CalculatorInterface
{

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku);

}
