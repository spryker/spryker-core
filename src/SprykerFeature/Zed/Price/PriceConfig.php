<?php

namespace SprykerFeature\Zed\Price;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class PriceConfig extends AbstractBundleConfig
{

    const DEFAULT_PRICE_TYPE = 'DEFAULT';

    /**
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return self::DEFAULT_PRICE_TYPE;
    }
}
