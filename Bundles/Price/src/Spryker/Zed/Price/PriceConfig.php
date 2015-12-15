<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price;

use Spryker\Zed\Kernel\AbstractBundleConfig;

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
