<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface;

class CarrierDiscountDecisionRule implements ShipmentDiscountDecisionRuleInterface
{
    /**
     * @var \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @var \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface $discountFacade
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface $shipmentFacade
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->isSatisfiedCarrier($quoteTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isExpenseSatisfiedBy(QuoteTransfer $quoteTransfer, ExpenseTransfer $expenseTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->isSatisfiedCarrier($quoteTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isSatisfiedCarrier(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $idShipmentCarrier = $this->getIdShipmentCarrierByQuote($quoteTransfer);

        if ($idShipmentCarrier && $this->discountFacade->queryStringCompare($clauseTransfer, $idShipmentCarrier)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function getIdShipmentCarrierByQuote(QuoteTransfer $quoteTransfer)
    {
        $shipment = $quoteTransfer->getShipment();

        if (!$shipment) {
            return null;
        }

        if ($shipment->getCarrier()) {
            return $shipment->getCarrier()->getIdShipmentCarrier();
        }

        if ($shipment->getMethod()) {
            $shipmentMethodTransfer = $this->shipmentFacade->findMethodById($shipment->getMethod()->getIdShipmentMethod());

            return $shipmentMethodTransfer->getFkShipmentCarrier();
        }

        return null;
    }
}
