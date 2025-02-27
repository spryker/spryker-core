<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Generated\Shared\Transfer\SalesReclamationItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesReclamationItemCollectionResponseTransfer;

interface SalesReclamationFacadeInterface
{
    /**
     * Specification:
     * - Creates new reclamation and reclamation item entities from ReclamationCreateRequestTransfer::Order and
     *   ReclamationCreateRequestTransfer::OrderItems
     *
     * - If incoming data inconsistent - return null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function createReclamation(ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer): ReclamationTransfer;

    /**
     * Specification:
     * - Changes reclamation state to close.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function closeReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer;

    /**
     * Specification:
     * - Maps order transfer to reclamation transfer.
     * - Maps nested order items transfers to reclamation items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapOrderTransferToReclamationTransfer(
        OrderTransfer $orderTransfer,
        ReclamationTransfer $reclamationTransfer
    ): ReclamationTransfer;

    /**
     * Specification:
     * - Returns reclamation entity by id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @throws \Spryker\Zed\SalesReclamation\Business\Exception\ReclamationNotFoundException
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function getReclamationById(ReclamationTransfer $reclamationTransfer): ReclamationTransfer;

    /**
     * Specification:
     * - Uses `SalesReclamationItemCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter sales reclamation item entities by the sales order item IDs.
     * - Deletes found by criteria sales reclamation item entities.
     * - Does nothing if no criteria properties are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesReclamationItemCollectionDeleteCriteriaTransfer $salesReclamationItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesReclamationItemCollectionResponseTransfer
     */
    public function deleteSalesReclamationItemCollection(
        SalesReclamationItemCollectionDeleteCriteriaTransfer $salesReclamationItemCollectionDeleteCriteriaTransfer
    ): SalesReclamationItemCollectionResponseTransfer;
}
