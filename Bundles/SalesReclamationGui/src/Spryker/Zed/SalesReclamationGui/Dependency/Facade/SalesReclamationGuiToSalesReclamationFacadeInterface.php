<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface SalesReclamationGuiToSalesReclamationFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    public function createReclamation(ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer): ?ReclamationTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function hydrateReclamationByOrder(OrderTransfer $orderTransfer): ReclamationTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    public function hydrateReclamationByIdReclamation(ReclamationTransfer $reclamationTransfer): ?ReclamationTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderReclamation(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;
}
