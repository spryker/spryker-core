<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;

class SalesOrderPluginExecutor implements SalesOrderPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\PreSaveOrderPluginInterface[]
     */
    protected $preSaveOrderPlugins;

    /**
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\PreSaveOrderPluginInterface[] $preSaveOrderPlugins
     */
    public function __construct(array $preSaveOrderPlugins)
    {
        $this->preSaveOrderPlugins = $preSaveOrderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function executePreSaveOrderPlugins(
        QuoteTransfer $quoteTransfer,
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer
    ): SpySalesOrderEntityTransfer {
        foreach ($this->preSaveOrderPlugins as $plugin) {
            $salesOrderEntityTransfer = $plugin->execute($quoteTransfer, $salesOrderEntityTransfer);
        }

        return $salesOrderEntityTransfer;
    }
}
