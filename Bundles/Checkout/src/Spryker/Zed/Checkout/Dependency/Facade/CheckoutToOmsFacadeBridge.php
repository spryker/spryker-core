<?php

namespace Spryker\Zed\Checkout\Dependency\Facade;


use Spryker\Zed\Oms\Business\OmsFacadeInterface;

class CheckoutToOmsFacadeBridge implements CheckoutToOmsFacadeInterface
{
    /**
     * @var OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, array $data = [])
    {
        return $this->omsFacade->triggerEventForNewOrderItems($orderItemIds, $data);
    }
}