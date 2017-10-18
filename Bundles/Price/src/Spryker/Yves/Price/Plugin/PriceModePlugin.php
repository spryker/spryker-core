<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Price\PriceFactory getFactory()
 * @method \Spryker\Yves\Price\PriceConfig getConfig()
 */
class PriceModePlugin extends AbstractPlugin implements PriceModePluginInterface
{

    /**
     * @return string
     */
    public function getCurrentPriceMode()
    {
        return $this->getFactory()
            ->createPriceModeResolver()
            ->getCurrentPriceMode();
    }

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return $this->getConfig()
            ->createSharedConfig()
            ->getGrossPriceModeIdentifier();
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return $this->getConfig()
            ->createSharedConfig()
            ->getNetPriceModeIdentifier();
    }
}
