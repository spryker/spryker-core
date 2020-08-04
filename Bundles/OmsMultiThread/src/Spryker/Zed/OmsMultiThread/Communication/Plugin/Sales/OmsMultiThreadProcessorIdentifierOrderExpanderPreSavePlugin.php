<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsMultiThread\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface;

/**
 * @method \Spryker\Zed\OmsMultiThread\Business\OmsMultiThreadFacadeInterface getFacade()
 * @method \Spryker\Zed\OmsMultiThread\OmsMultiThreadConfig getConfig()
 */
class OmsMultiThreadProcessorIdentifierOrderExpanderPreSavePlugin extends AbstractPlugin implements OrderExpanderPreSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands SpySalesOrderEntityTransfer with OMS processor identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function expand(SpySalesOrderEntityTransfer $salesOrderEntityTransfer, QuoteTransfer $quoteTransfer): SpySalesOrderEntityTransfer
    {
        return $this->getFacade()->expandSalesOrderEntityTransferWithOmsProcessorIdentifier($salesOrderEntityTransfer, $quoteTransfer);
    }
}
