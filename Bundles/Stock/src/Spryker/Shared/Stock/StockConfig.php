<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Stock;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class StockConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - This event is used for updating stock relations after a stock update.
     *
     * @api
     *
     * @var string
     */
    public const STOCK_POST_UPDATE_STOCK_RELATIONS = 'Stock.stock.post_update_stock_relations';
}
