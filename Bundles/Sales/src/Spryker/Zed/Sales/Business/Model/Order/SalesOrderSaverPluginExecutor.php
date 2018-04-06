<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

class SalesOrderSaverPluginExecutor implements SalesOrderSaverPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPreSavePluginInterface[]
     */
    protected $orderPreSavePlugins;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface[]
     */
    protected $orderItemExpanderPreSavePlugins;

    /**
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPreSavePluginInterface[] $orderPreSavePlugins
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface[] $orderItemExpanderPreSavePlugins
     */
    public function __construct(array $orderPreSavePlugins, array $orderItemExpanderPreSavePlugins)
    {
        $this->orderPreSavePlugins = $orderPreSavePlugins;
        $this->orderItemExpanderPreSavePlugins = $orderItemExpanderPreSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function executeOrderItemExpanderPreSavePlugins(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer
    {
        foreach ($this->orderItemExpanderPreSavePlugins as $plugin) {
            $salesOrderItemEntity = $plugin->expandOrderItem($quoteTransfer, $itemTransfer, $salesOrderItemEntity);
        }

        return $salesOrderItemEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function executeOrderPreSavePlugins(
        QuoteTransfer $quoteTransfer,
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer
    ): SpySalesOrderEntityTransfer
    {
        foreach ($this->orderPreSavePlugins as $plugin) {
            $salesOrderEntityTransfer = $plugin->execute($quoteTransfer, $salesOrderEntityTransfer);
        }

        return $salesOrderEntityTransfer;
    }

}
