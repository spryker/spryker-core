<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;

interface CalculatorInterface
{
    /**
     * @param DiscountTransfer[] $discountCollection
     * @param QuoteTransfer $quoteTransfer
     * @param DistributorInterface $discountDistributor
     *
     * @return mixed
     */
    public function calculate(
        array $discountCollection,
        QuoteTransfer $quoteTransfer,
        DistributorInterface $discountDistributor
    );

}
