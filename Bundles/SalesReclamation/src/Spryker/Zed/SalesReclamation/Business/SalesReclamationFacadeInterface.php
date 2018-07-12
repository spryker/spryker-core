<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

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
     * @return null|\Generated\Shared\Transfer\ReclamationTransfer
     */
    public function hydrateReclamationByOrder(OrderTransfer $orderTransfer): ReclamationTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderReclamation(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);
}
