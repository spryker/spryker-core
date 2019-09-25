<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver\MultiShipmentCollectorStrategyResolver;

/**
 * @method \Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorBusinessFactory getFactory()
 */
class ShipmentDiscountConnectorFacade extends AbstractFacade implements ShipmentDiscountConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getCarrierList()
    {
        return $this->getFactory()
            ->createShipmentDiscountReader()
            ->getCarrierList();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getMethodList()
    {
        return $this->getFactory()
            ->createShipmentDiscountReader()
            ->getMethodList();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectDiscountByShipmentCarrier(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createShipmentDiscountCollectorStrategyResolver()
            ->resolveByTypeAndItems(MultiShipmentCollectorStrategyResolver::DISCOUNT_TYPE_CARRIER, $quoteTransfer->getItems())
            ->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectDiscountByShipmentMethod(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createShipmentDiscountCollectorStrategyResolver()
            ->resolveByTypeAndItems(MultiShipmentCollectorStrategyResolver::DISCOUNT_TYPE_METHOD, $quoteTransfer->getItems())
            ->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectDiscountByShipmentPrice(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createShipmentDiscountCollectorStrategyResolver()
            ->resolveByTypeAndItems(MultiShipmentCollectorStrategyResolver::DISCOUNT_TYPE_PRICE, $quoteTransfer->getItems())
            ->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCarrierSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createShipmentDiscountDecisionRuleStrategyResolver()
            ->resolveByTypeAndItems(MultiShipmentCollectorStrategyResolver::DISCOUNT_TYPE_CARRIER, $quoteTransfer->getItems())
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isMethodSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createShipmentDiscountDecisionRuleStrategyResolver()
            ->resolveByTypeAndItems(MultiShipmentCollectorStrategyResolver::DISCOUNT_TYPE_METHOD, $quoteTransfer->getItems())
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isPriceSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createShipmentDiscountDecisionRuleStrategyResolver()
            ->resolveByTypeAndItems(MultiShipmentCollectorStrategyResolver::DISCOUNT_TYPE_PRICE, $quoteTransfer->getItems())
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }
}
