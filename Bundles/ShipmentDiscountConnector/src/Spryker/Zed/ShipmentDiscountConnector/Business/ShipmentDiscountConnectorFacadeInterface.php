<?php

namespace Spryker\Zed\ShipmentDiscountConnector\Business;


use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentDiscountConnectorFacadeInterface
{

    /**
     * Specification:
     * - Finds carriers in a database
     * - Returns assoc array [ID => Carrier name]
     *
     * @api
     *
     * @return array
     */
    public function getCarrierList();

    /**
     * Specification:
     * - Collects discountable items from the given quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectDiscountByShipmentCarrier(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Compare chosen for order shipment carrier with a carrier in condition
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCarrierSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer);

}