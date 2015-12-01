<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Distributor\DistributorInterface;
use SprykerFeature\Zed\Discount\DiscountConfigInterface;

interface CalculatorInterface
{

    /**
     * @param DiscountTransfer[] $discountCollection
     * @param CalculableInterface $container
     * @param DiscountConfigInterface $config
     * @param DistributorInterface $discountDistributor
     */
    public function calculate(
        array $discountCollection,
        CalculableInterface $container,
        DiscountConfigInterface $config,
        DistributorInterface $discountDistributor
    );

}
