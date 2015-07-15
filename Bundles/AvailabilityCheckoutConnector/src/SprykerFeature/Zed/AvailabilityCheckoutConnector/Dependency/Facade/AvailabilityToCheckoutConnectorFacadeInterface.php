<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\AvailabilityCheckoutConnector\Dependency\Facade;

interface AvailabilityToCheckoutConnectorFacadeInterface
{

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity);

}
