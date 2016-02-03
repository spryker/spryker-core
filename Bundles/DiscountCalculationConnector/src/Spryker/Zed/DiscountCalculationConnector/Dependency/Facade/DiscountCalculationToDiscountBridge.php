<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Orm\Zed\Discount\Persistence\SpyDiscount;

class DiscountCalculationToDiscountBridge implements DiscountCalculationToDiscountInterface
{

    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacade
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacade $discountFacade
     */
    public function __construct($discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
     */
    public function calculateDiscounts(QuoteTransfer $quoteTransfer)
    {
        return $this->discountFacade->calculateDiscounts($quoteTransfer);
    }

}
