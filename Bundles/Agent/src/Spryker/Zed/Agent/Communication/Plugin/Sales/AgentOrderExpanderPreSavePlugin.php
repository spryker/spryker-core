<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPreSavePluginInterface;

/**
 * @method \Spryker\Zed\Agent\AgentConfig getConfig()
 * @method \Spryker\Zed\Agent\Business\AgentFacadeInterface getFacade()
 */
class AgentOrderExpanderPreSavePlugin extends AbstractPlugin implements OrderExpanderPreSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds agent email to the sales order before save if isSalesOrderAgentEnabled feature flag is enabled.
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
        if (!$this->getConfig()->isSalesOrderAgentEnabled()) {
            return $salesOrderEntityTransfer;
        }

        if (method_exists($salesOrderEntityTransfer, 'setAgentEmail')) {
            $salesOrderEntityTransfer->setAgentEmail($quoteTransfer->getAgentEmail());
        }

        return $salesOrderEntityTransfer;
    }
}
