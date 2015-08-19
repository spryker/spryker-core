<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class ShipmentConfig extends AbstractBundleConfig
{
    const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * 100 means we can store numbers with two fractional digits (e.g. 1025 means 10.25)
     *
     * @return int
     */
    public function getPricePrecision()
    {
        return 100;
    }



}

