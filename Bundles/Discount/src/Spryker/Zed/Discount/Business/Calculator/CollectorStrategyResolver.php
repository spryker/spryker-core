<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CollectorStrategyResolver implements CollectorStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\CollectorStrategyPluginInterface[]
     */
    protected $collectorStrategyPlugins;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\CollectorStrategyPluginInterface[] $collectorStrategyPlugins
     */
    public function __construct(array $collectorStrategyPlugins)
    {
        $this->collectorStrategyPlugins = $collectorStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\CollectorStrategyPluginInterface|null
     */
    public function resolveCollector(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        foreach ($this->collectorStrategyPlugins as $collectorStrategyPlugin) {
            if (!$collectorStrategyPlugin->accept($discountTransfer, $quoteTransfer)) {
                continue;
            }

            return $collectorStrategyPlugin;
        }

        return null;
    }
}
