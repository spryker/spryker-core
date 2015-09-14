<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Distributor\DistributorInterface;
use SprykerFeature\Zed\Discount\DiscountConfigInterface;

interface CalculatorInterface
{

    /**
     * @param array $discountCollection
     * @param CalculableInterface $container
     * @param DiscountConfigInterface $settings
     * @param DistributorInterface $distributor
     */
    public function calculate(
        array $discountCollection,
        CalculableInterface $container,
        DiscountConfigInterface $settings,
        DistributorInterface $distributor
    );

}
