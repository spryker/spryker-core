<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class Aggregate implements CollectorInterface
{

    /**
     * @var CollectorInterface[]
     */
    protected $collectors = [];

    /**
     * @param CollectorInterface[] $collectors
     */
    public function __construct(array $collectors)
    {
        $this->collectors = $collectors;
    }

    /**
     * @param CalculableInterface $container
     *
     * @return OrderTransfer[]
     */
    public function collect(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer)
    {
        $collected = [];
        foreach ($this->collectors as $collector) {
            $collected = array_merge($collected, $collector->collect($container, $discountCollectorTransfer));
        }

        return $collected;
    }

}
