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
     * @api
     *
     * @return string[]
     */
    public function getPriceModes()
    {
        return $this->getSharedConfig()->getPriceModes();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultPriceMode()
    {
        return $this->getSharedConfig()->getDefaultPriceMode();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return $this->getSharedConfig()->getNetPriceModeIdentifier();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return $this->getSharedConfig()->getGrossPriceModeIdentifier();
    }
}
