<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeTransfer;

class ProductAlternativePluginExecutor implements ProductAlternativePluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductUpdateAlternativesPluginInterface[]
     */
    protected $postProductAlternativePlugins;

    /**
     * @var \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostDeleteProductAlternativePluginInterface[]
     */
    protected $postDeleteProductAlternativePlugins;

    /**
     * @param \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductUpdateAlternativesPluginInterface[] $postProductAlternativePlugins
     * @param \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostDeleteProductAlternativePluginInterface[] $postDeleteProductAlternativePlugins
     */
    public function __construct(
        array $postProductAlternativePlugins,
        array $postDeleteProductAlternativePlugins
    ) {
        $this->postDeleteProductAlternativePlugins = $postDeleteProductAlternativePlugins;
        $this->postProductAlternativePlugins = $postProductAlternativePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function executePostProductAlternativePlugins(ProductAlternativeTransfer $productAlternativeTransfer): void
    {
        foreach ($this->postProductAlternativePlugins as $postCreateProductAlternativePlugin) {
            $postCreateProductAlternativePlugin->execute($productAlternativeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function executePostDeleteProductAlternativePlugins(ProductAlternativeTransfer $productAlternativeTransfer): void
    {
        foreach ($this->postDeleteProductAlternativePlugins as $postDeleteProductAlternativePlugin) {
            $postDeleteProductAlternativePlugin->execute($productAlternativeTransfer);
        }
    }
}
