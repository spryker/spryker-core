<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;

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
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    public function createReclamation(ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer): ?ReclamationTransfer;

    /**
     * Specification:
     * - Updates existing sales reclamation entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function updateReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer;

    /**
     * Specification:
     * - Updates existing sales reclamation item entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function updateReclamationItem(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer;

    /**
     * Specification:
     * - Gets Reclamation by id from database.
     * - Expands Reclamation with data from database.
     * - Expands Reclamation items with data from database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    public function expandReclamationByIdReclamation(ReclamationTransfer $reclamationTransfer): ?ReclamationTransfer;

    /**
     * Specification:
     * - Expands Reclamation with data from order.
     * - Expands Reclamation items with data from order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function expandReclamationByOrder(OrderTransfer $orderTransfer): ReclamationTransfer;

    /**
     * Specification:
     * - Returns reclamation item entity by id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function getReclamationItemById(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer;

    /**
     * Specification:
     * - Returns reclamation entity by id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function getReclamationById(ReclamationTransfer $reclamationTransfer): ReclamationTransfer;
}
