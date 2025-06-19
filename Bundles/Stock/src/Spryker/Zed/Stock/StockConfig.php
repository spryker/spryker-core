<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class StockConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const TOUCH_STOCK_TYPE = 'stock-type';

    /**
     * @var string
     */
    public const TOUCH_STOCK_PRODUCT = 'stock-product';

    /**
     * Specification:
     * - For controlling stock related entities update, for example when only name has changed the stock update won't trigger product stock relation update.
     *
     * @api
     *
     * @return bool
     */
    public function isConditionalStockUpdateApplied(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - This event is used for updating stock relations after a stock update.
     *
     * @api
     *
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return null;
    }
}
