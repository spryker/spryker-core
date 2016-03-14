<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Model\DiscountableInterface;

class Aggregate implements CollectorInterface
{

    /**
     * @var \Spryker\Zed\Discount\Business\Collector\CollectorInterface[]
     */
    protected $collectors = [];

    /**
     * @param \Spryker\Zed\Discount\Business\Collector\CollectorInterface[] $collectors
     */
    public function __construct(array $collectors)
    {
        $this->collectors = $collectors;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Spryker\Zed\Discount\Business\Model\DiscountableInterface[]
     */
    public function collect(QuoteTransfer $quoteTransfer, DiscountCollectorTransfer $discountCollectorTransfer)
    {
        $collected = [];
        foreach ($this->collectors as $collector) {
            $collected = array_merge($collected, $collector->collect($quoteTransfer, $discountCollectorTransfer));
        }

        return $collected;
    }

}
