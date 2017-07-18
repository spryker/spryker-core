<?php


namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;


use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface;

class CarrierDiscountDecisionRule implements CarrierDiscountDecisionRuleInterface
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $currentItemTransfer, ClauseTransfer $clauseTransfer)
    {
        if (!$quoteTransfer->getShipment()) {
            return false;
        }

        if (!$quoteTransfer->getShipment()->getCarrier()) {
            return false;
        }

        $idShipmentCarrier = $quoteTransfer->getShipment()->getCarrier()->getIdShipmentCarrier();
        if ($this->discountFacade->queryStringCompare($clauseTransfer, $idShipmentCarrier)) {
            return true;
        }

        return false;
    }
}