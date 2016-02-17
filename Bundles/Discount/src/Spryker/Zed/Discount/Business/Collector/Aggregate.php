<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

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
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
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
