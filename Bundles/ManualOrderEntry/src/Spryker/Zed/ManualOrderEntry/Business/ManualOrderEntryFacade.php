<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Business;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ManualOrderEntry\Business\ManualOrderEntryBusinessFactory getFactory()
 */
class ManualOrderEntryFacade extends AbstractFacade implements ManualOrderEntryFacadeInterface
{
    /**
     * Specification:
     * - Returns the order source for the given order source id.
     *
     * @api
     *
     * @param int $idOrderSource
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer
     */
    public function findOrderSourceByIdOrderSource($idOrderSource): OrderSourceTransfer
    {
        return $this->getFactory()
            ->createOrderSourceManager()
            ->findOrderSourceByIdOrderSource($idOrderSource);
    }

    /**
     * Specification:
     * - Returns the order source for the given order source id.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer[]
     */
    public function findAllOrderSources(): array
    {
        return $this->getFactory()
            ->createOrderSourceManager()
            ->getAllOrderSources();
    }

    /**
     * Specification:
     *   - Adds OrderSource to SpySalesOrderEntityTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function hydrateOrderSource(
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer,
        QuoteTransfer $quoteTransfer
    ): SpySalesOrderEntityTransfer
    {
        return $this->getFactory()
            ->createOrderSourceManager()
            ->hydrateOrderSource($salesOrderEntityTransfer, $quoteTransfer);
    }
}
