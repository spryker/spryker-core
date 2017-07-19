<?php


namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;


use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface;

class PriceDiscountDecisionRule implements PriceDiscountDecisionRuleInterface
{

    /**
     * @var ShipmentDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @param ShipmentDiscountConnectorToDiscountInterface $discountFacade
     */
    public function __construct(ShipmentDiscountConnectorToDiscountInterface $discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ExpenseTransfer $expenseTransfer, ClauseTransfer $clauseTransfer)
    {
        $moneyAmount = $expenseTransfer->getUnitGrossPrice();

        if ($moneyAmount > 0) {
            $moneyAmount /= 100;
        }

        return $this->discountFacade->queryStringCompare($clauseTransfer, $moneyAmount);
    }

}