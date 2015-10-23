<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Distributor\DistributorInterface;
use SprykerFeature\Zed\Discount\DiscountConfigInterface;

interface CalculatorInterface
{

    /**
     * @param DiscountInterface[] $discountCollection
     * @param CalculableInterface $container
     * @param DiscountConfigInterface $settings
     * @param DistributorInterface $discountDistributor
     */
    public function calculate(
        array $discountCollection,
        CalculableInterface $container,
        DiscountConfigInterface $settings,
        DistributorInterface $discountDistributor
    );

}
