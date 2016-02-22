<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\DiscountConfigInterface;

interface CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discountCollection
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
