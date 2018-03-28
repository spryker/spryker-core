<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Sales;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SalesConfig extends AbstractBundleConfig
{
    public const ORDER_TYPE_DEFAULT = null;

    /**
     * @return null|string
     */
    public function getOrderTypeDefault()
    {
        return static::ORDER_TYPE_DEFAULT;
    }
}
