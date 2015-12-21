<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

interface AvailabilityToStockInterface
{

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku);

}
