<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PriceProduct\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\PriceProduct\PriceProductConfig getConfig()
 */
class PriceProductPlugin extends AbstractPlugin implements PriceProductPluginInterface
{

    /**
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return $this->getConfig()
            ->createSharedPriceConfig()
            ->getPriceTypeDefaultName();
    }
}
