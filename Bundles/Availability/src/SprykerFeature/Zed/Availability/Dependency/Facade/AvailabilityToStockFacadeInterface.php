<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Availability\Dependency\Facade;

interface AvailabilityToStockFacadeInterface
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
