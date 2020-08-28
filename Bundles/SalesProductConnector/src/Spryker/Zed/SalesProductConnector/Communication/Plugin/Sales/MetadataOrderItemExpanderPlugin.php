<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig getConfig()
 */
class MetadataOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands order items with metadata information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemTransfers): array
    {
        return $this->getFacade()->expandOrderItemsWithMetadata($itemTransfers);
    }
}
