<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;

class ProductDiscontinuedPluginExecutor implements ProductDiscontinuedPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostProductDiscontinuePluginInterface[]
     */
    protected $postProductDiscontinuePlugins;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostDeleteProductDiscontinuedPluginInterface[]
     */
    protected $postDeleteProductDiscontinuedPlugins;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostDeleteBulkProductDiscontinuedPluginInterface[]
     */
    protected $postDeleteBulkProductDiscontinuedPlugins;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostProductDiscontinuePluginInterface[] $postProductDiscontinuePlugins
     * @param \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostDeleteProductDiscontinuedPluginInterface[] $postDeleteProductDiscontinuedPlugins
     * @param \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostDeleteBulkProductDiscontinuedPluginInterface[] $postDeleteBulkProductDiscontinuedPlugins
     */
    public function __construct(
        array $postProductDiscontinuePlugins,
        array $postDeleteProductDiscontinuedPlugins,
        array $postDeleteBulkProductDiscontinuedPlugins
    ) {
        $this->postDeleteProductDiscontinuedPlugins = $postDeleteProductDiscontinuedPlugins;
        $this->postProductDiscontinuePlugins = $postProductDiscontinuePlugins;
        $this->postDeleteBulkProductDiscontinuedPlugins = $postDeleteBulkProductDiscontinuedPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function executePostProductDiscontinuePlugins(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void
    {
        foreach ($this->postProductDiscontinuePlugins as $postCreateProductDiscontinuePlugin) {
            $postCreateProductDiscontinuePlugin->execute($productDiscontinuedTransfer);
        }
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedPluginExecutor::executeBulkPostDeleteProductDiscontinuedPlugins()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function executePostDeleteProductDiscontinuedPlugins(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void
    {
        foreach ($this->postDeleteProductDiscontinuedPlugins as $postDeleteProductDiscontinuedPlugin) {
            $postDeleteProductDiscontinuedPlugin->execute($productDiscontinuedTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return void
     */
    public function executeBulkPostDeleteProductDiscontinuedPlugins(ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer): void
    {
        $this->executePostDeleteProductDiscontinuedPluginsForProductDiscontinuedCollection($productDiscontinuedCollectionTransfer);

        foreach ($this->postDeleteBulkProductDiscontinuedPlugins as $postDeleteBulkProductDiscontinuedPlugin) {
            $postDeleteBulkProductDiscontinuedPlugin->execute($productDiscontinuedCollectionTransfer);
        }
    }

    /**
     * @deprecated Added for BC reasons.
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return void
     */
    protected function executePostDeleteProductDiscontinuedPluginsForProductDiscontinuedCollection(
        ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
    ): void {
        if (!$this->postDeleteProductDiscontinuedPlugins) {
            return;
        }

        foreach ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts() as $productDiscontinuedTransfer) {
            $this->executePostDeleteProductDiscontinuedPlugins($productDiscontinuedTransfer);
        }
    }
}
