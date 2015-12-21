<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\AvailabilityCartConnector\Dependency\Facade;

interface AvailabilityCartConnectorToAvailabilityInterface
{

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity);

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku);

}
