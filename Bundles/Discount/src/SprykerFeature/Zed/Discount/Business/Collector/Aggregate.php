<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\DiscountCollectorInterface;
use Generated\Shared\Discount\OrderInterface;
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
     * @return OrderInterface[]
     */
    public function collect(CalculableInterface $container, DiscountCollectorInterface $discountCollectorTransfer)
    {
        $collected = [];
        foreach ($this->collectors as $collector) {
            $collected = array_merge($collected, $collector->collect($container, $discountCollectorTransfer));
        }

        return $collected;
    }

}
