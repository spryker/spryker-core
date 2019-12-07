<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceCartConnectorConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_REMOVE
     */
    public const OPERATION_REMOVE = 'remove';
}
