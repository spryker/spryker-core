<?php


namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;


use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface;

class MethodDiscountDecisionRule implements MethodDiscountDecisionRuleInterface
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
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ExpenseTransfer $expenseTransfer , ClauseTransfer $clauseTransfer)
    {
        $shipment = $quoteTransfer->getShipment();

        if (!$shipment) {
            return false;
        }

        $idShipmentMethod = null;

        if ($shipment->getMethod()) {
            $idShipmentMethod = $shipment->getMethod()->getIdShipmentMethod();
        }

        if ($idShipmentMethod && $this->discountFacade->queryStringCompare($clauseTransfer, $idShipmentMethod)) {
            return true;
        }

        return false;
    }

}