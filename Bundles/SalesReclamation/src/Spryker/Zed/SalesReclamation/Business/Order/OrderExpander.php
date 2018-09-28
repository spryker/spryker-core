<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Order;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface
     */
    protected $salesReclamationFacade;

    /**
     * @param \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface $salesReclamationFacade
     */
    public function __construct(SalesReclamationToSalesFacadeInterface $salesReclamationFacade)
    {
        $this->salesReclamationFacade = $salesReclamationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $spySalesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function expandSalesOrderEntity(
        SpySalesOrderEntityTransfer $spySalesOrderEntityTransfer,
        QuoteTransfer $quoteTransfer
    ): SpySalesOrderEntityTransfer {
        $spySalesOrderEntityTransfer->setFkSalesReclamation($quoteTransfer->getReclamationId());

        return $spySalesOrderEntityTransfer;
    }
}
