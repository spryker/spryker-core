<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use Generated\Shared\Calculation\CalculableContainerInterface;
use Generated\Shared\Sales\OrderInterface;

class CalculableContainer implements CalculableInterface
{

    /**
     * @var OrderInterface
     */
    private $order;

    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * @return CalculableContainerInterface
     */
    public function getCalculableObject()
    {
        return $this->order;
    }

}
