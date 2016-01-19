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
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @param DiscountFacade $discountFacade
     */
    public function __construct($discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return SpyDiscount[]
     */
    public function calculateDiscounts(QuoteTransfer $quoteTransfer)
    {
        return $this->discountFacade->calculateDiscounts($quoteTransfer);
    }

}
