<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price\Plugin;

/**
 * @method \Spryker\Yves\Price\PriceFactory getFactory()
 * @method \Spryker\Yves\Price\PriceConfig getConfig()
 */
interface PriceModePluginInterface
{
    /**
     * @return string
     */
    public function getCurrentPriceMode();

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier();

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier();
}
