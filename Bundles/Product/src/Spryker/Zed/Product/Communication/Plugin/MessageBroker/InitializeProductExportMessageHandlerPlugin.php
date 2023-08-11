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
 * @deprecated Use {@link \Spryker\Zed\Product\Communication\Plugin\MessageBroker\ProductExportMessageHandlerPlugin} instead.
 *
 * @method \Spryker\Zed\Product\Business\ProductFacadeInterface getFacade()
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 */
class InitializeProductExportMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers the Product.product_concrete.export event for all existing products.
     * - Each event contains ProductConcrete ID.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\InitializeProductExportTransfer $initializeProductExportTransfer
     *
     * @return void
     */
    public function onProductExportInitialized(InitializeProductExportTransfer $initializeProductExportTransfer): void
    {
        $storeReference = $initializeProductExportTransfer->getMessageAttributesOrFail()->getStoreReference();
        $productExportCriteriaTransfer = (new ProductExportCriteriaTransfer())
            ->setStoreReference($storeReference);

        $this->getFacade()->triggerProductExportEvents($productExportCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     * - Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        return [InitializeProductExportTransfer::class => [$this, 'onProductExportInitialized']];
    }
}
