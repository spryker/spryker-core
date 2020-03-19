<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductSetGuiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function prependLocaleForProductSetUrl()
    {
        return true;
    }
}
