<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Availability\Business\Model;

interface SellableInterface
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
