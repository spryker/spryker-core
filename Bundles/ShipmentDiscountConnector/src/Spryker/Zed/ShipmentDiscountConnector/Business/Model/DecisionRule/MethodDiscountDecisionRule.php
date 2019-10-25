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

/**
 * @deprecated Use \Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\MethodDiscountDecisionRule instead.
 */
class MethodDiscountDecisionRule implements ShipmentDiscountDecisionRuleInterface
{
    /**
     * @var \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface $discountFacade
     */
    public function __construct(ShipmentDiscountConnectorToDiscountInterface $discountFacade)
    {
        $this->discountFacade = $discountFacade;
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
        return $this->isSatisfiedMethod($quoteTransfer, $clauseTransfer);
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
        return $this->isSatisfiedMethod($quoteTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isSatisfiedMethod(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $shipment = $quoteTransfer->getShipment();

        if (!$shipment) {
            return false;
        }

        $idShipmentMethod = null;

        if ($shipment->getMethod()) {
            $idShipmentMethod = $shipment->getMethod()->getIdShipmentMethod();
        }

        if ($idShipmentMethod && $this->discountFacade->queryStringCompare($clauseTransfer, (string)$idShipmentMethod)) {
            return true;
        }

        return false;
    }
}
