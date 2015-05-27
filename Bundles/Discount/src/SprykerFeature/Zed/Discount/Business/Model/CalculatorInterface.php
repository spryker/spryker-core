<?php

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Discount\Business\DiscountSettingsInterface;

interface CalculatorInterface
{

    /**
     * @param array $discounts
     * @param OrderInterface $container
     * @param DiscountSettingsInterface $settings
     * @param DistributorInterface $distributor
     */
    public function calculate(
        array $discounts,
        OrderInterface $container,
        DiscountSettingsInterface $settings,
        DistributorInterface $distributor
    );
}
