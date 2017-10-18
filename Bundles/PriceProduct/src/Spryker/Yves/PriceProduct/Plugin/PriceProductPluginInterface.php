<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PriceProduct\Plugin;

/**
 * @method \Spryker\Yves\PriceProduct\PriceProductConfig getConfig()
 */
interface PriceProductPluginInterface
{
    /**
     * @return string
     */
    public function getPriceTypeDefaultName();
}
