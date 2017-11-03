<?php

namespace Spryker\Zed\Checkout\Dependency\Facade;


use Spryker\Zed\Oms\Business\OmsFacadeInterface;

interface CheckoutToOmsFacadeInterface
{
    /**
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, array $data = []);
}