<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\DiscountConfigInterface;

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
