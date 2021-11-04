<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Sales;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SalesConfig extends AbstractSharedConfig
{
    /**
     * @var array<string>
     */
    protected const ORDER_SEARCH_TYPES = [
        'all',
        'orderReference',
        'itemName',
        'itemSku',
    ];
}
