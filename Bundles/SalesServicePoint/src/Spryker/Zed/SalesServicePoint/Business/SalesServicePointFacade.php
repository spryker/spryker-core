<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesServicePoint\Business\SalesServicePointBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface getEntityManager()
 */
class SalesServicePointFacade extends AbstractFacade implements SalesServicePointFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemServicePointsFromQuote(QuoteTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createSalesOrderItemServicePointsSaver()
            ->saveSalesOrderItemServicePointsFromQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithServicePoint(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createServicePointExpander()
            ->expandOrderItemsWithServicePoint($itemTransfers);
    }
}
