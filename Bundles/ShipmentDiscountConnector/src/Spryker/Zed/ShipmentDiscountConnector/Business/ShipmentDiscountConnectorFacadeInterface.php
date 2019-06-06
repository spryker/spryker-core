<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
     * - Finds shipment methods in a database
     * - Returns assoc array [ID => Method name]
     *
     * @api
     *
     * @return array
     */
    public function getMethodList();

    /**
     * Specification:
     * - Collects discountable items from the given quote by the quote level (BC) or item level shipments' carriers.
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
     * - Collects discountable items from the given quote by the quote level (BC) or item level shipments' methods.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectDiscountByShipmentMethod(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Collects discountable items from the given quote by the quote level (BC) or item level shipments' prices.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectDiscountByShipmentPrice(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Compare the shipment carrier selected in the quote level (BC) or item level with the shipment carrier in condition.
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

    /**
     * Specification:
     * - Compare the shipment method selected in the quote level (BC) or item level with the shipment method in condition.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isMethodSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Compare the shipment price selected in the quote level (BC) or item level with the shipment price in condition.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isPriceSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer);
}
