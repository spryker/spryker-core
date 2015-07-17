<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\DiscountSettingsInterface;

interface CalculatorInterface
{

    /**
     * @param array $discounts
     * @ param OrderInterface $container
     *
     * @param CalculableInterface $container
     * @param DiscountSettingsInterface $settings
     * @param DistributorInterface $distributor
     */
    public function calculate(
        array $discounts,
        //OrderInterface $container,
        CalculableInterface $container,
        DiscountSettingsInterface $settings,
        DistributorInterface $distributor
    );

}
