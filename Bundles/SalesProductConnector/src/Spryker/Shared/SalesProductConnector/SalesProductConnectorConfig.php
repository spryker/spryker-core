<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SalesProductConnector;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SalesProductConnectorConfig extends AbstractSharedConfig
{
    /**
     * Specification
     * - Constant is used to group popularity product-related product page data expanders.
     *
     * @api
     */
    public const PLUGIN_PRODUCT_POPULARITY_DATA = 'PLUGIN_PRODUCT_POPULARITY_DATA';
}
