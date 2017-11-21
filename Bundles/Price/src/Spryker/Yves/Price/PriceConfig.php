<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price;

use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Price\PriceConfig getSharedConfig()
 */
class PriceConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getPriceModes()
    {
        return $this->getSharedConfig()->getPriceModes();
    }

    /**
     * @return string
     */
    public function getDefaultPriceMode()
    {
        return $this->getSharedConfig()->getDefaultPriceMode();
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return $this->getSharedConfig()->getNetPriceModeIdentifier();
    }

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return $this->getSharedConfig()->getGrossPriceModeIdentifier();
    }
}
