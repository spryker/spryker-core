<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued;

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
     * @param \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostProductDiscontinuePluginInterface[] $postProductDiscontinuePlugins
     * @param \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostDeleteProductDiscontinuedPluginInterface[] $postDeleteProductDiscontinuedPlugins
     */
    public function __construct(
        array $postProductDiscontinuePlugins,
        array $postDeleteProductDiscontinuedPlugins
    ) {
        $this->postDeleteProductDiscontinuedPlugins = $postDeleteProductDiscontinuedPlugins;
        $this->postProductDiscontinuePlugins = $postProductDiscontinuePlugins;
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
}
