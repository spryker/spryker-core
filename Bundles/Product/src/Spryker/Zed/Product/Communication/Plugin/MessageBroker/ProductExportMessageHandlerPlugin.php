<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\InitializeProductExportTransfer;
use Generated\Shared\Transfer\ProductExportCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\Product\Business\ProductFacadeInterface getFacade()
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 */
class ProductExportMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Handles `InitializeProductExport` message by triggering `Product.product_concrete.export` events for all concrete products.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        return [
            InitializeProductExportTransfer::class => function () {
                $this->getFacade()->triggerProductExportEvents((new ProductExportCriteriaTransfer()));
            },
        ];
    }
}
