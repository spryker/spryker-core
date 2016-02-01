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
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Spryker\Zed\Discount\DiscountConfigInterface $config
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface $discountDistributor
     */
    public function calculate(
        array $discountCollection,
        CalculableInterface $container,
        DiscountConfigInterface $config,
        DistributorInterface $discountDistributor
    );

}
