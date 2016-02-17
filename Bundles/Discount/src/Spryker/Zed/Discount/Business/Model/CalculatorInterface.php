<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;

interface CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discountCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface $discountDistributor
     *
     * @return mixed
     */
    public function calculate(
        array $discountCollection,
        QuoteTransfer $quoteTransfer,
        DistributorInterface $discountDistributor
    );

}
