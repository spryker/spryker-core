<?php


namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;


use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface;

class CarrierDiscountDecisionRule implements CarrierDiscountDecisionRuleInterface
{

    /**
     * @var ShipmentDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @var ShipmentDiscountConnectorToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @param ShipmentDiscountConnectorToDiscountInterface $discountFacade
     * @param ShipmentDiscountConnectorToShipmentInterface $shipmentFacade
     */
    public function __construct(
        ShipmentDiscountConnectorToDiscountInterface $discountFacade,
        ShipmentDiscountConnectorToShipmentInterface $shipmentFacade
    ) {
        $this->discountFacade = $discountFacade;
        $this->shipmentFacade = $shipmentFacade;
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
        $shipment = $quoteTransfer->getShipment();

        if (!$shipment) {
            return false;
        }

        $idShipmentCarrier = null;

        if ($shipment->getCarrier()) {
            $idShipmentCarrier = $shipment->getCarrier()->getIdShipmentCarrier();
        }

        if ($shipment->getMethod()) {
            $shipmentMethodTransfer = $this->shipmentFacade->findMethodById($shipment->getMethod()->getIdShipmentMethod());
            $idShipmentCarrier = $shipmentMethodTransfer->getFkShipmentCarrier();
        }

        if ($idShipmentCarrier && $this->discountFacade->queryStringCompare($clauseTransfer, $idShipmentCarrier)) {
            return true;
        }

        return false;
    }
}