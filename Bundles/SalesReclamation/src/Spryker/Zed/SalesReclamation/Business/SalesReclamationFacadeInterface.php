<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;

interface SalesReclamationFacadeInterface
{
    /**
     * Specification:
     * - Create new Reclamation by SalesOrder and SalesOrderItemIds
     * - If incoming data inconsistent - return null
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return null|\Generated\Shared\Transfer\ReclamationTransfer
     */
    public function createReclamation(ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer): ?ReclamationTransfer;

    /**
     * Specification:
     * - Update existing sales reclamation entity.
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
     * - Update existing sales reclamation item entity.
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
     * - Get Reclamation by id from database
     * - Hydrate Reclamation with data from database
     * - Hydrate Reclamation items with data from database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return null|\Generated\Shared\Transfer\ReclamationTransfer
     */
    public function hydrateReclamationByIdReclamation(ReclamationTransfer $reclamationTransfer): ?ReclamationTransfer;

    /**
     * Specification:
     * - Hydrate Reclamation with data from order
     * - Hydrate Reclamation items with data from order items
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function hydrateReclamationByOrder(OrderTransfer $orderTransfer): ReclamationTransfer;

    /**
     * Specification:
     * - Expands SpySalesOrderEntityTransfer with reclamation id before order created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $spySalesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function expandSalesOrderEntity(
        SpySalesOrderEntityTransfer $spySalesOrderEntityTransfer,
        QuoteTransfer $quoteTransfer
    ): SpySalesOrderEntityTransfer;

    /**
     * Specification:
     * - Fetch all reclamations which contain at least one reclamation item.
     *
     * @api
     *
     * @return \ArrayObject
     */
    public function getReclamations(): ?ArrayObject;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function getReclamationItemById(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function getReclamationById(ReclamationTransfer $reclamationTransfer): ReclamationTransfer;
}
